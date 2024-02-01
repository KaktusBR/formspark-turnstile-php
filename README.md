# Contact Form with Cloudflare Turnstile and Formspark

This repository contains files for a simple contact form that integrates with Cloudflare Turnstile for captcha verification and submits data to Formspark.

## Files

1. **submit-form.php**: This PHP file handles the form submission, validates the Turnstile captcha, and sends data to Formspark.

2. **Form.astro**: This Astro (HTML/JSX) file contains the structure of the contact form along with the necessary scripts for handling form submission.

## Usage

1. **Form Configuration**:

    - Open `Form.astro` and replace `<SITE_KEY>` with your actual Cloudflare site key.

    - Open `submit-form.php` and replace `<SECRET_KEY>` with your actual Cloudflare Turnstile secret key.

    - Replace `<FORMSPARK_FORM_ID>` with the actual Formspark form endpoint in `submit-form.php`.

    - Ensure that you have a custom honeypot field in your form, and replace `<CUSTOM_HONEYPOT_FIELD>` in `submit-form.php`and `Form.astro` with the actual name of your honeypot field.

2. **Build and Upload to Server**:

    - Run `npm run build` to build the project. This will generate a `dist` folder.

    - Upload the contents of the `dist` folder to your server.

3. **Configure Formspark**:

    - Ensure that your Formspark form is properly configured to receive data.

4. **Access the Form**:

    - Access the form by navigating to the appropriate URL on your server where `Form.astro` is located.

5. **Test the Form**:

    - Test the form by submitting data. Check the browser console for any errors.

6. **Troubleshooting**:

    - If there are issues with captcha verification or Formspark submission, review the error messages in the browser console and server logs.

7. **Additional Notes**:

    - This project is set up to be used with Astro - SSG (Static Site Generator). It may not work on Node.js servers, and alternative configurations may be needed.

    - The form can be easily adapted for vanilla HTML or PHP projects. Simply remove Astro-specific syntax and adapt the scripts accordingly.

    - Ensure that your server supports PHP for the server-side script to work.

    - Make sure to handle sensitive information securely, especially the Cloudflare Turnstile secret key.

    - For more information on Cloudflare Turnstile and Formspark, refer to their respective documentation.

