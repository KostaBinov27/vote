# Votes Plugin

This plugin is developed for WordPress on local Docker env. 

## Features

- Yes / No buttons for voting under each post
- One time voting feature per post
- Displaying voting statistics after voting
- Responsive design
- Meta widget included in the posts

## Technologies used in this plugin

This plugin is built under wppb and it is using OOP.

- PHP
- jQuery
- JavaScript
- HTML
- CSS
- Ajax

## Installation

This plugin is Plug & Play. All you need to do is get the zip from this repo, upload to your website and the voting box will appear under each post.

## Development

All functionalities decribed:

In this file ```wp-content/plugins/votes/public/class-votes-public.php```  are developed all functionalities needed for the public part of the plugin. Enqued all scritps and stylesheets. Also there are callbacks for displaying the voting box, functionality for voting (storing data in database), calculations, IP detection and more.

In this file ```wp-content/plugins/votes/public/patials/votes-public-display.php``` is the basic HTML of the voting box.

This JS file ```wp-content/plugins/votes/public/js/votes-ajax-public.js``` contains the Ajax call.

This CSS file ```wp-content/plugins/votes/public/css/votes-public.css``` contains the stylehseet for the voting box.

In this file ```wp-content/plugins/votes/admin/class-votes-admin.php``` are developed all functionalities needed for the admin part of the plugin. Displaying the meta box and getting the results.

In this file ```wp-content/plugins/votes/admin/partials/votes-admin-display.php``` are developed all functionalities needed for the admin part of the plugin. Displaying the meta box and getting the results.