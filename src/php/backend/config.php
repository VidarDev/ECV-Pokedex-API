<?php

// Retrieves variables from the .env file
function loadEnv() {
    $envPath = __DIR__ . '/../../../.env';
    if (!file_exists($envPath)) {
        throw new Exception('.env file not found');
    }

    // Read all lines from the .env file and return an array, ignoring newlines and empty lines.
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Remove leading and trailing spaces, and check if the line starts with '#'.
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments
        }

        // Separate the line into two parts on the first '=' sign found. To get the variable and its value.
        list($name, $value) = explode('=', $line, 2);

        // Remove leading and trailing spaces around the variable name and value.
        $name = trim($name);
        $value = trim($value);

        // Check if the variable is not already defined in $_SERVER or $_ENV.
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            // Set the environment variable globally for the current script.
            putenv(sprintf("%s=%s", $name, $value));

            // Add the variable and its value to the $_ENV and $_SERVER superglobal arrays.
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

loadEnv();