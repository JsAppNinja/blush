#blush
=====
joinblush.com

## Deployment

* This site does not use FTP to deploy changes - Changes are automatically deployed from the master branch of git *

The application is modeled as a Stack at Amazon on the OpsWorks platforms.  The purpose of this is to allow the site
to autoscale with traffic. As traffic increases, new servers are brought online to meet the demand.  As traffic decreases,
the additional servers will automatically shutdown.

When code changes are written, the developer will commit them to the *master* branch.  When a change is committed to the
master branch, Github will kick off a webhook that causes OpsWorks to deploy the code changes.

## How To Deploy ##

1. Download the latest master branch from github and create your changes.
2. Test the changes by ftping them to blush.scmreview.com (you can get connection info from the spacechimp internal project staging logins document)
3. Commit the changes to github on the master branch.  Github will call out to AWS OpsWorks kicking off a deployment
4. If you want to watch the progress, go to OpsWorks under the Spacechimp Account and look at the "blush" stack.
5. You should see under the deployments section a new deployment taking place.

## SASS & CSS

Updating CSS: This site uses Compass to compile SASS into CSS.  All styling updates should be done in the /www/assets/sass/*.scss files.
If you make any changes directly to the css code, it will be overwritten.

To use compass, (http://compass-style.org/), follow the installation instructions from their website.
When you make changes to the scss files, you can run `compass watch` from the /www/assets/ directory and it will
automatically compile your scss files into the stylesheets.
