# Pocket Money Strict Link Opener

A PHP-based system that allows users to create protected links. When someone opens a protected link, they must answer specific security questions correctly before being redirected to the intended destination. This helps ensure that only authorized individuals can access the target link and prevents bots from automatically following links.

## Features

- Create links protected by custom security questions
- Case-sensitive answer validation
- Sequential question answering with progress tracking
- Secure storage of link data
- Mobile-friendly responsive design

## Requirements

- PHP 7.0 or higher
- Web server with PHP support (Apache, Nginx, etc.)
- Write permissions for the `data` directory

## Installation

1. Clone or download this repository to your web server directory:
   ```
   git clone https://github.com/yourusername/Pocket-Money-Strict-Link-Opener.git
   ```

2. Ensure the `data` directory is writable by your web server:
   ```
   chmod 755 data
   ```

3. Configure your web server to serve the application.

## Usage

### Creating Protected Links

1. Visit the homepage (index.php)
2. Enter the destination URL you want to protect
3. Add security questions and their corresponding answers
   - You can add multiple questions for increased security
   - Answers are case-sensitive
4. Click "Generate Protected Link"
5. Copy the generated link to share with others

### Accessing Protected Links

1. Open the protected link in a browser
2. Answer each security question correctly
3. After all questions are answered correctly, you'll be redirected to the intended destination

## Security Considerations

- The system uses case-sensitive matching for answers
- Questions are presented sequentially, not all at once
- Link IDs are randomly generated using cryptographically secure methods
- All stored data is in a directory that should be protected from direct web access

## License

This project is open-source and available under the MIT License.

## Support

For issues or feature requests, please open an issue on GitHub. 