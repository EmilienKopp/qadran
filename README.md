# Qadran

A modern web application built for small teams, freelancers and start-ups to efficiently track time, manage projects, and gain valuable insights into their work patterns. Built with Laravel, Inertia.js, Svelte, and PostgreSQL.

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technical Architecture](#technical-architecture)
- [Requirements](#requirements)
- [Getting Started](#getting-started)
- [Usage Guide](#usage-guide)
- [Project Structure](#project-structure)
- [Contributing](#contributing)
- [License](#license)

## Overview

This application streamlines time tracking through an intuitive clock-in/clock-out system. Users can:

- Track daily and weekly working hours with precision
- Associate time sessions with specific projects or administrative tasks
- Generate comprehensive reports for time analysis
- Break down work sessions into specific activities (optional)

The system separates time logs from activity breakdowns, allowing users to record their work sessions while maintaining the flexibility to allocate time to specific tasks later.

## Features

### User Management
- Secure authentication system
- Role-based access control (Admin/Employer/Freelancer/Employee)

### Project Management
- Create and manage multiple projects
- Organize tasks within projects
- Handle standalone administrative tasks
- Track project-specific time and budget allocations

### Time Tracking
- Intuitive clock in/out interface
- Automatic session duration calculation
- Project-specific or task-specific time logging
- Activity breakdown options

### Reporting & Analytics
- Daily and weekly time summaries
- Project-based time analysis
- Custom date range filtering
- Detailed activity breakdowns
- Visual data representations

### Voice Commands
- Client-side voice commands for quick navigation
- Hands-free browsing experience
- Commands for page navigation, scrolling, and voice assistant control
- Keyboard shortcut: Ctrl/Cmd + Shift + C
- Support for natural language patterns

## Technical Architecture

### Core Technologies
- Backend: Laravel 11+
- Frontend: Svelte 5
- Middleware: Inertia.js 2.0
- Database: PostgreSQL 17
- Authentication: Laravel Breeze

## Getting Started

### Installation

1. Clone the repository:
```bash
git clone https://github.com/EmilienKopp/qadran.git
cd qadran
```

2. Install dependencies:
```bash
composer install
npm install # or pnpm install
```

3. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
docker compose up
```

4. Update `.env` with your PostgreSQL credentials:
```ini
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=54329
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Set up the database:
```bash
php artisan migrate
php artisan db:seed  # Optional: adds demo data
```

### Development Server

#### All in one
```bash
npm run start
```

#### Manual

1. Start the Laravel server:
```bash
php artisan serve
```

2. Compile frontend assets:
```bash
npm run dev
```

3. Start db server:
```bash
docker compose up
```

## Usage Guide

### Time Tracking Workflow

1. Project Selection
   - Choose a project from your dashboard
   - Use "Admin" for general tasks

2. Clock Operations
   - Click "Clock In" to start a session
   - Work on your tasks
   - "Clock Out" when finished or switching projects

3. Activity Breakdown (Optional)
   - Split completed sessions into specific activities
   - Tag activities with relevant task IDs
   - Add notes or descriptions

### Reporting

1. Access the dashboard for:
   - Daily/weekly hour summaries
   - Project-specific time allocation
   - Activity breakdowns

2. Generate custom reports:
   - Filter by date range
   - Select specific projects
   - Export data in various formats

### Voice Commands

The application includes client-side voice commands for hands-free navigation and control. These commands use the Web Speech Recognition API and work entirely in the browser without requiring server communication.

#### Activating Voice Commands

- **Button**: Click the "Voice Command" button in the Voice Assistant widget (bottom-right corner)
- **Keyboard**: Press `Ctrl + Shift + C` (or `Cmd + Shift + C` on Mac)
- **Speech**: Say "help" to see all available commands

#### Available Commands

**Navigation Commands:**
- "go to projects" / "open projects"
- "go to organizations" / "open organizations"
- "go to rates" / "open rates"
- "go to reports" / "open reports"
- "go to settings" / "open settings"
- "go to profile" / "my profile"

**Browser Navigation:**
- "go back" / "back"
- "go forward" / "forward"
- "refresh" / "reload page"

**Scrolling:**
- "scroll down" / "page down"
- "scroll up" / "page up"
- "scroll to top" / "go to top"
- "scroll to bottom" / "go to bottom"

**Voice Assistant Control:**
- "start voice listening" / "enable voice"
- "stop voice listening" / "disable voice"

**Help:**
- "help" / "show commands" - Display all available voice commands

#### Browser Compatibility

Voice commands require a modern browser with Web Speech Recognition API support:
- Google Chrome (recommended)
- Microsoft Edge
- Safari (partial support)
- Firefox (limited support)

If your browser doesn't support voice commands, the feature will be hidden automatically.

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

Please ensure your PR:
- Follows the existing code style
- Includes appropriate tests
- Updates documentation as needed
- Describes the changes in detail

## License

See the [LICENSE](LICENSE) file for details.

---

For additional support or questions, please open an issue in the repository.