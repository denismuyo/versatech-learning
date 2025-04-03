<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["submission"])) {
    $to = "denisnzioka63@gmail.com"; // Replace with your email
    $subject = "New Task Submission";
    $message = "A user has submitted a task.";
    $headers = "From: noreply@yourwebsite.com";

    $file = $_FILES["submission"]["tmp_name"];
    $fileName = $_FILES["submission"]["name"];

    if (move_uploaded_file($file, "uploads/" . $fileName)) {
        $attachment = "uploads/" . $fileName;
        $boundary = md5(time());

        // Email headers
        $headers .= "\nMIME-Version: 1.0";
        $headers .= "\nContent-Type: multipart/mixed; boundary=\"$boundary\"";

        // Email body
        $body = "--$boundary\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\n";
        $body .= "Content-Transfer-Encoding: 7bit\n\n";
        $body .= $message . "\n\n";
        $body .= "--$boundary\n";
        $body .= "Content-Type: application/octet-stream; name=\"$fileName\"\n";
        $body .= "Content-Disposition: attachment; filename=\"$fileName\"\n";
        $body .= "Content-Transfer-Encoding: base64\n\n";
        $body .= chunk_split(base64_encode(file_get_contents($attachment))) . "\n";
        $body .= "--$boundary--";

        if (mail($to, $subject, $body, $headers)) {
            echo "File uploaded and email sent successfully.";
        } else {
            echo "Failed to send email.";
        }
    } else {
        echo "File upload failed.";
    }
} else {
    echo "No file uploaded.";
}
?>
