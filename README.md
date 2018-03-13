# ContactFormHandler

This is a contact form handler script. It accepts a form-data submission containing any combination of fields, does some basic spam checking, then generates an email containing the form field keys and values, and sends it to the list of recipients.

Required Fields
===============

`trap`
------
The `trap` field is intended as a honeypot trap, so it must be empty, otherwise the form submission will be discarded as spam. On the front-end, it is intended that a text field hidden with CSS will be included as part of the HTML form. Spam bots will fill this field out, but humans will leave it blank because it will be invisible.

`email`
-------
Not a required field, but if this field is set, the form will set the email 'Reply-To' header to this value.

Return Value
============
On success, the contact form will respond with the string `success`. On failure, no response will be provided.
