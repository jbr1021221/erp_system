<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Form - {{ $student->name ?? 'Student' }}</title>
    <style>
        /* Base Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        /* Print Button */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #5a67d8;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .print-button {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <button class="print-button no-print" onclick="window.print()">
        🖨️ Print Form
    </button>

    @include('students.partials.admission-form-content', ['student' => $student])

    <script>
        // Auto-print functionality (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
