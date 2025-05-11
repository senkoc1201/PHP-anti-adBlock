# Anti-Adblock Project

## Project Overview
A sophisticated anti-adblock solution designed to protect and manage digital advertising content while maintaining user experience. The system implements advanced fingerprinting and click monitoring to detect and prevent ad blocking attempts.

## Key Features
1. **Advanced Fingerprinting System**
   - Utilizes ThumbmarkJS for unique user identification
   - Implements browser fingerprinting to track user behavior
   - Maintains user session tracking across multiple visits

2. **Intelligent Ad Unit Management**
   - Supports multiple ad formats (AdSense, GAM)
   - Dynamic ad unit loading and monitoring
   - Custom ad unit identification system
   - Real-time ad unit status tracking

3. **Click Monitoring System**
   - Real-time click tracking on ad units
   - Automatic detection of suspicious click patterns
   - Integration with iframe monitoring
   - 1-second interval monitoring for click validation

4. **Administrative Control Panel**
   - Secure admin interface for system management
   - Click count reset functionality
   - User access control
   - System monitoring capabilities

5. **Security Features**
   - IP address tracking
   - Fingerprint-based user identification
   - Secure API endpoints for data transmission
   - Protection against iframe manipulation

## Technical Implementation
- **Frontend**: JavaScript, HTML, CSS
- **Backend**: PHP
- **Database**: MySQL
- **APIs**: 
  - Google AdSense integration
  - Google Ad Manager (GAM) integration
  - Custom API endpoints for click tracking

## System Architecture
1. **Client-Side Components**
   - Ad unit detection and management
   - Click monitoring system
   - Fingerprint generation
   - Real-time status updates

2. **Server-Side Components**
   - Click tracking system
   - User blocking management
   - Admin panel interface
   - API endpoints for data processing

## Security Measures
- Fingerprint-based user identification
- IP address tracking
- Secure API communication
- Protection against iframe manipulation
- Click pattern analysis

## User Experience
- Seamless ad integration
- Non-intrusive monitoring
- Quick response to user interactions
- Easy-to-use admin interface

## Integration Capabilities
- Compatible with major ad networks
- Support for multiple ad formats
- Flexible implementation options
- Customizable ad unit management

## Project Structure
```
├── adminpanel/         # Administrative interface
├── controllers/        # Backend controllers
├── css/               # Stylesheets
├── js/                # JavaScript files
│   └── adshield.js    # Core anti-adblock functionality
├── install/           # Installation files
├── config.php         # Configuration settings
├── header.php         # Common header template
├── index.php          # Main entry point
└── testads*.php       # Test implementation files
```

## Setup and Installation
1. Configure database settings in `config.php`
2. Set up the admin panel credentials
3. Integrate ad units using the provided templates
4. Deploy the system to your web server

## Requirements
- PHP 7.0 or higher
- MySQL database
- Web server (Apache/Nginx)
- Modern web browser support

## License
[Specify your license here]

## Contact
[Your contact information] 