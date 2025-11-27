<!DOCTYPE html>
<html>
<head>
    <title>Debug Quiz Subject Loading</title>
</head>
<body>
    <h1>Debug Quiz Subject Loading</h1>
    
    <div>
        <h2>Test 1: Check Route URL</h2>
        <p>Expected URL pattern: <code>/tutor/packages/{id}/subjects</code></p>
        <p id="route-url"></p>
    </div>
    
    <div>
        <h2>Test 2: Manual Fetch Test</h2>
        <button onclick="testFetch()">Test Fetch Package 1 Subjects</button>
        <pre id="fetch-result"></pre>
    </div>
    
    <div>
        <h2>Test 3: Check Authentication</h2>
        <p id="auth-status"></p>
    </div>
    
    <script>
        // Test 1: Show what the route helper generates
        const routeTemplate = "{{ route('tutor.packages.subjects', ':id') }}";
        const actualUrl = routeTemplate.replace(':id', '1');
        document.getElementById('route-url').innerHTML = `
            <strong>Route template:</strong> ${routeTemplate}<br>
            <strong>Actual URL for package 1:</strong> <code>${actualUrl}</code>
        `;
        
        // Test 2: Manual fetch
        function testFetch() {
            const resultEl = document.getElementById('fetch-result');
            resultEl.textContent = 'Loading...';
            
            fetch(actualUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                resultEl.textContent = `Status: ${response.status} ${response.statusText}\n`;
                return response.text();
            })
            .then(text => {
                resultEl.textContent += `\nResponse:\n${text}`;
                
                // Try to parse as JSON
                try {
                    const json = JSON.parse(text);
                    resultEl.textContent += `\n\nParsed JSON:\n${JSON.stringify(json, null, 2)}`;
                } catch (e) {
                    resultEl.textContent += `\n\nNot valid JSON. Error: ${e.message}`;
                }
            })
            .catch(error => {
                resultEl.textContent = `Error: ${error.message}`;
            });
        }
        
        // Test 3: Check auth
        fetch('/tutor/dashboard')
            .then(response => {
                const authEl = document.getElementById('auth-status');
                if (response.redirected || response.status === 401) {
                    authEl.innerHTML = '<strong style="color: red;">❌ Not authenticated - redirected to login</strong>';
                } else {
                    authEl.innerHTML = '<strong style="color: green;">✓ Authenticated</strong>';
                }
            });
    </script>
</body>
</html>
