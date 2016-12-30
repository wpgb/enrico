=== Enrico ===
Contributors: gbellak
Tags: company directory, Eniro API, google map, eniro map, eniro.se, krak.dk, gulesider.no
Requires at least: 3.0.1
Tested up to: 4.6.1
Stable tag: 1.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Utilise Eniro's public API to build and maintain your own directory of companies

== Description ==

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


== Installation ==

1. Upload the Enrico plugin to your blog

2. Activate Enrico in your Admin plugins dashboard

3. In your blogs Admin panel - go to Settings/Enrico and enter your Eniro API credentials (Register for free API key from https://api.eniro.com/ if you don't have this yet)

4. In the Settings page you can select some options for the Enrico plugin, e.g. which map service you prefer Eniro or Google Maps (or possibly None). 

5. Go to Enrico Directory and add custom categories (e.g. Resellers, Suppliers, Customers)

6. Go to Enrico Directory and start adding companies, either one by one if you already know the Eniro-ID of the company, or via the Import panel

7. Once you have published some companies chek if the enrico templates are working:
            - YOUR URL/enrico/ ...should show you a archive page of all your companies
            - YOUR URL/YOUR CATEGORYSLUG/YOUR CATEGORYTERM ...should show you a list of all your companies in a CATEGORY/TERM e.g. YOUR URL/partners/suppliers 
            - Clicking on a single entry will link to a single post page
            - NB! You need to provide a link to the enrico archive pages:
                    e.g. if you creare a partner typ called "Clients" - the slug will be "clients",
                    when you have registered some company entries (in the Enrico Directory) and assigned them to
                    the partner type clients- you can then visit the page "YOUR URL/partners/clients" to see your clients archive.
                    It s recommended that you create a link on your blog page (e.g. in the Menu bar) which will take you to the Clients archive.
                    

8. The plugin uses the following Templates (located in the plugins template folder):
             archive-enrico.php (to display the full archive under YOUR URL/enrico/)
             single-enrico.php (to display a singel enrico company post)
             taxonomy-partner_type.php (to display taxonomy archive URL/YOUR CATEGORYSLUG/YOUR CATEGORYTERM)
             
9. You will most probably want to customize the 3 templates above to fit the design of your blog. 
    To do this it's recommended that you copy the templates to you themeroot (keep their names!)- and custimize the copies- so you can refresh the plugin without ovewriting your customizations!
    The plugin will try to find the said templates in your themeroot first- before using the ones in the plugin theme folder.


== Screenshots ==

             
1. In your blogs Admin panel - go to Settings/Enrico and enter your Eniro API credentials plus preferred settings.
    (Register for free API key from https://api.eniro.com/ if you don't have this yet)

2. Go to Enrico Directory - Partner Categories and add your custom categories (e.g. Resellers, Suppliers, Customers)

3. Go to Enrico Directory and add companies, one by one- you need only the Eniro ID to fetch all fields

4. Search for companies and import several at a time 
    (either Interactively -where you can deselect/select the companies to import, or Bulk where import is done in the background)
    
5. YOUR URL/enrico/ ...should show you a archive page of all your companies

6. YOUR URL/YOUR CATEGORYSLUG/YOUR CATEGORYTERM ...should show you a list of all your companies in a CATEGORY/TERM

7. The single (enrico) post is shown on a separate page, however the template (single-enrico.php) needs to be adapted to your theme to look good...

8. Refresh all directory entries (fetch new eniro information)