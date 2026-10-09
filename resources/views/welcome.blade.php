<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Workshop Registration System</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: #f3f4f6;
            color: #111827;
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            width: 100%;
            max-width: 760px;
            padding: 36px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }

        h1 {
            margin: 0 0 12px;
            font-size: 28px;
        }

        .description {
            margin-bottom: 24px;
            color: #6b7280;
            line-height: 1.6;
        }

        .login-button {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 6px;
            background: #2563eb;
            color: #ffffff;
            font-weight: 600;
            text-decoration: none;
        }

        .login-button:hover {
            background: #1d4ed8;
        }

        h2 {
            margin-top: 32px;
            font-size: 19px;
        }

        .account {
            margin-top: 12px;
            padding: 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .account h3 {
            margin: 0 0 10px;
            font-size: 16px;
        }

        .account p {
            margin: 6px 0;
            overflow-wrap: anywhere;
            color: #4b5563;
        }

        code {
            padding: 2px 5px;
            border-radius: 4px;
            background: #f3f4f6;
            color: #111827;
        }

        .note {
            margin-top: 24px;
            color: #6b7280;
            font-size: 13px;
            line-height: 1.5;
        }

        @media (max-width: 520px) {
            .container {
                padding: 24px;
            }

            h1 {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>
    <main class="container">
        <h1>Workshop Registration System</h1>

        <p class="description">
            Manage workshops, attendee registrations, cancellations,
            waitlists, and registration history through the administration
            panel.
        </p>

        <a class="login-button" href="{{ url('/admin/login') }}">
            Open Login Page
        </a>

        <h2>Sample Login Accounts</h2>

        <section class="account">
            <h3>Admin</h3>
            <p>Email: <code>admin@example.com</code></p>
            <p>Password: <code>Password123!</code></p>
        </section>

        <section class="account">
            <h3>Manager</h3>
            <p>Email: <code>manager@example.com</code></p>
            <p>Password: <code>Password123!</code></p>
        </section>

        <section class="account">
            <h3>Staff</h3>
            <p>Email: <code>staff@example.com</code></p>
            <p>Password: <code>Password123!</code></p>
        </section>

        <p class="note">
            These credentials are for local development and demonstration
            purposes only. Do not expose sample passwords on a production
            website.
        </p>
    </main>
</body>
</html>
