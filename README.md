# Calendly

This is a simple Laravel application that allows users to schedule events and appointments, and create Zoom meetings for
those appointments.

## Installation

1. Clone the repository to your local machine:
```js
git clone https://github.com/milyastayan/calendly.git
```
2. Install the application dependencies using Composer:
```js
   cd calendly
   composer install
```
3. Create a new `.env` file by copying the `.env.example` file:
```js
   cp .env.example .env
```
4. Generate a new application key:
```js
   php artisan key:generate
```
5. Set up your database connection by updating the `DB_` variables in your `.env` file.

6. Run the database migrations and seed the database with sample data:
   php artisan migrate --seed

   ## Configuration
   ### Zoom API
   This application integrates with the Zoom API to create meetings for appointments. To use this feature, you will need
   to obtain a Zoom API key and secret.

Sign up for a Zoom account at https://zoom.us.
Log in to the Zoom Developer Dashboard at https://marketplace.zoom.us/develop.
Click on "Create" to create a new app.
Follow the steps to create a JWT app and obtain your API key and secret.
Add your Zoom API key and secret to the .env file:

```js
ZOOM_API_URL="https://api.zoom.us/v2/"
ZOOM_API_KEY="YOUR KEY"
ZOOM_API_SECRET="YOUR SECRET KEY"
```
### Email
This application uses SMTP to send emails for appointment confirmations and reminders. To use this feature, you will
need to update the mail configuration settings in the .env file with your own email service provider details:

```js
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME="USERNAME"
MAIL_PASSWORD="PASSWORD"
MAIL_FROM_ADDRESS="Email"
MAIL_ENCRYPTION=tls
```
## Usage
### Creating Events
To create a new event, log in to the application and navigate to the Events page. Click on the "Create Event" button and
fill in the details for the event. Click "Save" to create the event.

### Scheduling Appointments
To schedule a new appointment for an event, navigate to the event custom link and click on the "Schedule Appointment"
button. Fill in the details for the appointment and click "Save" to create the appointment.

### Zoom Meetings
This application automatically creates Zoom meetings for appointments. When an appointment is created, a Zoom meeting
will be created and the meeting link will be included in the appointment confirmation email.

### Appointment Reminders
This application sends email reminders to guests before their scheduled appointments. By default, a reminder email will
be sent one hour before the appointment time.

## Credits
This application was created by Muhammed ilyas tayan. If you have any questions or feedback, please contact milyastayan@gmail.com.

