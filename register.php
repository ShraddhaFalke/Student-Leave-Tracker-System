<?php include '../includes/db_connection.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Student Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        /* Reset & base */
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #fff; /* plain white background */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .registration-card {
            background: #fff;
            padding: 50px 60px;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(107, 0, 160, 0.15);
            width: 760px;  /* wider form */
            max-width: 100%;
            border: 2px solid #6a0dad;
        }

        .registration-card h2 {
            color: #6a0dad;
            font-weight: 700;
            font-size: 2.4rem;
            margin-bottom: 30px;
            text-align: center;
            letter-spacing: 1.3px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 24px 40px; /* vertical and horizontal spacing */
        }

        /* Each form group takes about half width except full width fields */
        .form-group {
            flex: 1 1 45%; /* flexible grow, basis ~45% width */
            display: flex;
            flex-direction: column;
        }

        /* For full width fields like address and password */
        .form-group.full-width {
            flex-basis: 100%;
        }

        label {
            font-weight: 600;
            color: #6a0dad;
            margin-bottom: 8px;
            font-size: 1.05rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"],
        textarea {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #b388eb;
            border-radius: 8px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            color: #333;
            font-family: inherit;
            resize: vertical;
            box-shadow: inset 0 2px 5px rgba(180, 136, 235, 0.15);
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        input[type="password"]:focus,
        textarea:focus {
            border-color: #6a0dad;
            box-shadow: 0 0 12px rgba(106, 13, 173, 0.5);
            outline: none;
            background-color: #faf5ff;
        }

        textarea {
            min-height: 90px;
        }

        button.btn-primary {
            background-color: #6a0dad;
            color: white;
            font-weight: 700;
            padding: 18px;
            font-size: 1.3rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            letter-spacing: 1px;
            width: 100%;
            margin-top: 10px;
            flex-basis: 100%;
        }

        button.btn-primary:hover {
            background-color: #4b0082;
        }

        .text-center {
            text-align: center;
            margin-top: 22px;
            flex-basis: 100%;
        }

        .btn-link {
            color: #6a0dad;
            text-decoration: underline;
            font-weight: 600;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .btn-link:hover {
            color: #4b0082;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 820px) {
            form {
                flex-direction: column;
            }
            .form-group {
                flex-basis: 100%;
            }
            button.btn-primary {
                flex-basis: 100%;
            }
        }
    </style>
</head>
<body>

<div class="registration-card">
    <h2>Student Registration</h2>
    <form action="register_process.php" method="POST" autocomplete="off">
        <div class="form-group">
            <label for="student_id">Student ID</label>
            <input type="text" id="student_id" name="student_id" required />
        </div>

        <div class="form-group">
            <label for="class">Class</label>
            <input type="text" id="class" name="class" required />
        </div>

       
        

        <div class="form-group">
            <label for="student_phone">Student Phone Number</label>
            <input type="tel" id="student_phone" name="student_phone" pattern="[0-9]{10}" maxlength="10" placeholder="10-digit phone number" required />
        </div>

        <div class="form-group">
            <label for="parent_phone">Parent's Phone Number</label>
            <input type="tel" id="parent_phone" name="parent_phone" pattern="[0-9]{10}" maxlength="10" placeholder="10-digit phone number" required />
        </div>

        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required />
        </div>


        <div class="form-group full-width">
            <label for="email">Student Email</label>
            <input type="email" id="email" name="email" placeholder="example@mail.com" required />
        </div>

        <div class="form-group full-width">
            <label for="address">Residence Address</label>
            <textarea id="address" name="address" rows="3" required></textarea>
        </div>

        <div class="form-group full-width">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required />
        </div>

        <button type="submit" class="btn-primary">Register</button>

        <div class="text-center">
            <a href="login.php" class="btn-link">Already have an account?</a>
        </div>
    </form>
</div>

</body>
</html>
