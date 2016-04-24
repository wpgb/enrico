# enrico
Eniro is one of the largest search companies in the Nordic region- and their public API ("Company search - basic") provides
information about companies in Sweden (eniro.se), Denmark (krak.dk), and Norway (gulesider.no)

This Wordpress plugin is utilising Eniro's public API to build and maintain your own directory of companies with both 
contact information and geo-data (location) supplied by Eniro. The data received from Eniro can be supplemented with your own
information fields and categorization to customize your own company directory.

This greatly reduces the effort needed for registering companies, geocoding their locations and keeping the information up to date.

Basic templates to browse and present your company directory is included, with the
company locations displayed on a map (Eniromap or Googlemap depending on the preferred choice).

Eniro api key and profile is needed to make all this magic work!

Pls register for your own eniro api key here:
http://api.eniro.com/


Installation:

1. Copy the entire Enrico folder into your wordpress sites plugin directory (/.../wp-content/plugins)
2. Activate Enrico in your Admin plugins dashboard
3. Go to Settings/Enrico and enter your Eniro API credentials (Register for free API key from https://api.eniro.com/ if you don't have this yet)
4. In the Settings page you can select which map service you prefer Eniro or Google Maps (or possibly None) 
5. Go to Enrico Directory and start adding companies, either one by one if you already know the Eniro-ID of the company, or via the Import panel

