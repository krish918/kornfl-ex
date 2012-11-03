# Starting on gitHub is simple enough 
> Git is world's most popular Version/revision control system. 
  Large Enterprises like Facebook, twitter, Microsoft all host their open source project on gitHub.
  Its simply *de-facto standard* for industry.
  
## What to do first 
You can start in two ways.
* * * *
 * Either download the whole source code as a zip package and you are done. You got the source code, and now keeping in mind the [License](http://www.apache.org/licenses/LICENSE-2.0), do any thing you want to.
 * The second way is **more fun** and **more educational**. It will give you chance to **learn Git**, which will be most crucial part of your life as a computer science professional. You will be collaborating with several other people over the same project. Read the following to work through gitHub.

## Working through gitHub

 + First create an account on gitHub.
 + Download **Git Bash**. It is almost like the same shells used in unix.
 + Come to this Kornfl-ex repository. Click on **Fork**.

  > By clicking Fork, you are getting a copy of project for your own work. It diverges the code from the           original source.

### Working on Git Bash

 1. You haven't got a local copy yet. For this do the following.
     - Open Git Bash.
     - Navigate to the directory where you want to store your Forked project		 
     - Make a directory, of some desired name, then follow the command sequence as

                $ cd d:                       //your desired location
                $ mkdir .kornfl-ex            //your desired directory name
                $ git init                   //initiates all the necessary files
                $ git clone https://github.com/*your gitHub username*/kornfl-ex.git


 1. Conrats! Now you have local copy of kornfl-ex project. You can start working on it.
 1. The copy you just cloned will not contain any changes that will pushed in the repo by me. That may make         your copy obsolete. 


    > Any change in the work is known as `commits`. `Commits` contain detailed report of changes made to 	    files and codes. By exploring commits of any project you can be sure of at what time what amendments 	    were introduced.


 1. So, to be updated with any furthur `commits` for Kornfl-ex repository, you will have to do the               following :

	          $ git remote add upstream https://github.com/krish918/kornfl-ex.git
		    
                    //Note that this time url contains my username, not yours
	               
     
	    
 > You have just created a **remote** which points to official repository. Name of remote is
  `upstream`. It can be any of your choices, but its convention. When you clone a repo, 
   a default remote `origin` is created. Thus right now, you have two remotes. `origin` points
   to your copy of forked repo. `upstream` points to the original repo, which will help you to
   pull furthur commits from me. You can see current remotes :

                  $ git remote -v


 1. Now, its time to see if any new `commits` for your forked repo is present or not. There are two ways.
 I'll advise to use the second.
  - The first one does all the job for you. It will pull any commits and immediately merge with the
       local repo. (Local repo means copy of repo on your disk).   

             $ git pull upstream
  - The second one involves two steps.


                          $ git fetch upstream             // Brings new commits to local repo
                          $ git merge upstream/master 	   // Merges commits to local branch


	  
    > Difference between `pull` and `fetch` is that, `pull` immediately merges commits to your 	            branch. With `fetch` you are given a chance to work with new commits locally and then merge 	            with your working branch later.

 1. Now, you are almost done. Now, work on your local project and push it back to gitHub as follows.

                          $ git add file1.php
                          $ git add file2.php
                          $ git add file3.php

                               // it queues all the files you have edited or changed, which are to be commited

                          $ git commit -m "My first commit"

                              // Commits all the changes you made with a message 'my first commit'

                          $ git push origin master

                              // Pushes all the commits to your gitHub account.
                              // Here origin is the name of remote which points to your forked
                              // gitHub repo and master is the branch you are working in, which is default.

	                    		
 1. You have pushed changes to your repo. But, the changes have not been merged with the original repo, 
       i.e. my repo. To request merging of your code in original repo, send me a Pull Request, by clicking
       on Pull Request button, in your gitHub account.

* * * * *

##If you are confused 

  Lets have a look at basic gitHub terminology.
###Repository 
  [Repository](http://en.wikipedia.org/wiki/Repository_\(version_control\)) is to codes, what library is to books. Its collection of codes, routines, documentations and everything related to your projected. It is also called a repo. Kornfl-ex repository will contain all the
collection of my work.
###Version/Revision control system
  [Git](http://en.wikipedia.org/wiki/Git_\(software\)) is a [Version control system](http://en.wikipedia.org/wiki/Revision_control). It simply means its a software, which will take care of your software
  development cycle. In most simple way, it will tell you that at what time, your project was at what point.
  You can revert back to any other previous state of your project, if you think that some older version was   better than new one.
###gitHub
  [gitHub](http://en.wikipedia.org/wiki/GitHub) helps you to collaborate with people over your project and at the same time controls your versions
  through Git mechanism.
###Commits
  Commits are any changes you brought at any point of time in your project. All commits are stored in commit
  history. You can see at what time you were working on what and what changes did you make.
###Remote
  Remote is a repo stored on any other computer. If you want to bring some repos to your system, you will use
  a remote which will point to a particular repo.
###Fork
  When you fork a repo, it means you are bringing a copy of project from the author's account to yours account.
###Pull request
  Its a request sent to the a original author of the repo to ask him to add your work to his repo.

- - - -

## Still confused
  If you have any queries and need assistance [visit the official guide](https://help.github.com/articles/set-up-git), by gitHub. You can comment on this commit to communicate with me.
  You can also connect to me through Email : s3_krish@yahoo.co.in 

				 	