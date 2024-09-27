This is some code for a website that will be used to upload treadmill workouts to Strava.


Feature list:
- Only allow users to access the page where you are able to add workouts to strava after loggin in
- Be able to add workouts to strava



PREREQ:
- understand how PHP works. 
    Need a general understanding, also have these questions:
    ? How do you get a user action to trigger PHP code to run?
    ? How does the syntax work?

TODO:
I. landing page (optional)
    1. build UI
        a. give a description of the website
    2. build functionality
        a. button that brings you to the login page

II. Login page
    1. build UI
        a. need a form for users to enter in username and password
        b. continue button
    2. build functionality (there is something wrong in login.php)
        a. hitting the 'continue' button has to trigger interaction between client and server, this is where PHP is   supposed to come in. PHP is how you define the give instructions for client and server interactions
            - need this to check the username and password combo
                - the AJAX is sending the POST request with the right data, there is something wrong in login.php
            - need to create a 'session' and store user with 'session'
                ? how do sessions work ?

III. Create new user page (probably not needed for this website)
    1. does this have to be different than the login page? probably not? might be easier to have it as a seperate page for now, lowkey don't even think we need this. I am going to be the only user. Can just store my username/password combo. 

IV. Strava Treadmill Function Page
    1. Build UI
        a. need form for the user to enter the workout the want to add
        b. need a logout button (optional)
    2. need to add API calls that create new workouts for my strava account based on the information in the form


