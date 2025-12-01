#!/bin/bash
echo "Starting PinePix server on http://localhost:3000"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""
php -S localhost:3000 -t public router.php

