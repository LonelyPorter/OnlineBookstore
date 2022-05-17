# BookStore (Android app)

***

## How to install/run
* Requirement tools:
  * Android Studio (including Java SDK)
  * PHP
  * Xampp
* Prepare:
  * Put the `bookstore` folder under the project folder (`/AndroidStudioProjects`) you specify during the installation of Android Studio
  * Put the `Phase3` folder (the ones containing all the PHP files) under
  `~/xampp/htdocs`
  * This app is running the same database as Phase2, so you can re-run the `~/DB2/DB2.sql` to set up the database if you haven't set it up before
  * Inside the Android App folder, modified the file located in `~\AndroidStudioProjects\bookstore\app\src\main\res\values\strings.xml`
    * under ```<string name='url'>``` change the IP address to your own local IP address *(x.x.x.x)*
* Run:
  * Start `Mysql`, `Apache` in Xampp
  * Set up the database if needed
  * Open the project `bookstore` inside Android Studio
  * After build, hit launch app

***

## Features
#### 1. A guest searches for a book by title and/or author

* Hit `Guest` in the main page
* Enter Titile/Author and hit `search` to show all the results

#### 2. A customer logs in and checks their order history

* Enter email and password to log in
  * Example:  
    Email: LydiaSchwartz@gmail.com  
    Password: 1001
* On top of the screen click `Order`, results will then be shown as table on the screen

#### Extra:
1. User can sign up as Author/Normal Customer
1. After log in, users can see all books on the store main page
1. Customers can view his/her own information in the profile page (the profile icon)
1. Customers can change their membership status (prime <-> non prime)
(*Guest cannot update their status)
1. Customers can view the detail information of a particular book after clicking on the title of the book on the store main page. They can then add the book to their shopping carts by clicking the *add* button at the bottom
1. Customers can check their shopping cart by clicking the shopping cart icon on the top right corner. They can delete their entries or click `purchase` to buy those books
1. Publishers can log in with their names and see their information and books they publish