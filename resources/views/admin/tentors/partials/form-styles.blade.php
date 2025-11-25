    <style>
        .tentor-form-wrapper {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .tentor-form-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            padding: 32px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        .tentor-form-card h2 {
            margin: 0 0 8px;
            font-size: 1.4rem;
            color: #0f172a;
        }

        .tentor-form-card p {
            margin: 0 0 24px;
            color: #64748b;
        }

        .tentor-form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        .tentor-form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .tentor-form-group label {
            font-weight: 600;
            color: #0f172a;
            font-size: 0.95rem;
        }

        .tentor-form-group input,
        .tentor-form-group textarea,
        .tentor-form-group select {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px;
            font-family: inherit;
            font-size: 0.95rem;
            background: #f8fafc;
            transition: border 0.2s, box-shadow 0.2s;
        }

        .tentor-form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .tentor-form-group input:focus,
        .tentor-form-group textarea:focus,
        .tentor-form-group select:focus {
            outline: none;
            border-color: #0f766e;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.15);
        }

        .tentor-avatar-field {
            display: flex;
            gap: 20px;
            align-items: center;
            padding: 24px;
            border: 1px dashed #cbd5e1;
            border-radius: 16px;
            background: #f8fafc;
        }

        .tentor-avatar-field img {
            width: 96px;
            height: 96px;
            border-radius: 999px;
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }

        .tentor-status-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            border-radius: 16px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
        }

        .tentor-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn-primary {
            background: #0f766e;
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 12px 24px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.1s ease;
        }

        .btn-primary:hover {
            background: #115e59;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: transparent;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            border-radius: 999px;
            padding: 12px 20px;
            font-weight: 600;
            cursor: pointer;
            transition: border 0.2s;
        }

        .btn-secondary:hover {
            border-color: #94a3b8;
        }

        .danger-card {
            border: 1px solid #fecaca;
            background: #fef2f2;
            border-radius: 16px;
            padding: 24px;
        }

        .danger-card h3 {
            margin: 0 0 8px;
            color: #b91c1c;
        }

        .danger-card p {
            margin: 0 0 16px;
            color: #7f1d1d;
        }

        .danger-card button {
            background: #dc2626;
            border: none;
            color: #fff;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            cursor: pointer;
        }

        .helper-text {
            color: #64748b;
            font-size: 0.85rem;
        }

        .subject-selection {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 12px;
        }

        .subject-group h4 {
            font-size: 0.9rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 10px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid rgba(15, 23, 42, 0.1);
        }

        .subject-checkboxes {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            background: rgba(248, 250, 252, 0.5);
            border: 1px solid rgba(15, 23, 42, 0.08);
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .checkbox-label:hover {
            background: rgba(248, 250, 252, 1);
            border-color: rgba(15, 23, 42, 0.15);
        }

        .checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            margin: 0;
        }

        @media (max-width: 768px) {
            .subject-checkboxes {
                grid-template-columns: 1fr;
            }
        }
    </style>
