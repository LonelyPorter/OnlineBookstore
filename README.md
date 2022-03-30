# Online Bookstore

***

## Description:

The purpose of this project is to create a bookstore database to perform regular functions of normal online bookstore like Amazon.  
First, design a bookstore database. Then build webpages on top of the database using HTML, PHP and MySQL.  

### How to run
* Run Xampp after download
 * start `Apache`, `MySQL`
 * put the entire folder `DB2` under `htdocs` inside the `xampp` root folder
* In the shell (from Xampp) run: ```mysql -u root bookstore < DB2.sql```  
* Type the URL in the browser [localhost/DB2](localhost/DB2)

***

## 1. A new customer registers, upgrades from non-member to member.

 Click on `Sign up` from login page to register as a new customer. After log in, click on `Prime` to enroll/unenroll the membership status.  

 Test Example:
* Email: YujinAn@gmail.com
* Password: 1111
* Name: Yujin An
* Phone: 9787354952
* Address: 11 School St, Lowell, MA, 01850
* Role: Author
* Upgrade Member: `Non-Prime`<---->`Prime`

***

## 2. A publisher adds a new book with author information to the database, updates price of a book.

From login page, click on `Publisher` to login in as publisher by entering the correct(corresponding) publisher name. Once the publisher logs in, click on `Add Books` to add new book with author information. On publisher homepage, click on `Update Price` to update books price.

Test Example:
* Publisher: Akashic Books
* Add Book:
  * ISBN: 9780671027032
  * Title: How to Win Friends & Influence People
  * Type: paperback
  * Price: 16.99
  * Category: Social Contact
  * In_Stock: 10
  * Method: paperback
  * Author: Yujin An
* Update Price:
  * "How to Win Friends & Influence People"

***

## 3. The admin (superuser) updates the cost of shipping methods for books  

From login, enter the superuser credential to log in as superuser. Click on `Update` from store main page to update the costs of a particular shipping method of the bookstore books.

Test Example:
* Email: TinaMaldonado@gmail.com
* Password: 1000
* Update:
  * Cost: 3.99
  * Method: loose leaf

***

## 4. A customer searches for a particular book by title and/or author and purchases the book.

Log in with an existing customer. Click on `Search` to search a particular book by title and/or author name. It can click on `add to cart` to add it into the user's shopping cart.
In shopping cart, the user can hit `purchase` to buy all books in the shopping cart.  
\*User need to have a valid address and a payment method to purchase books\*

Test Example:
* Email: LydiaSchwartz@gmail.com
* Password: 1001
* Search:
  * Title: Calculus
  * Author: Yujin An
  * Title or/and Author be NULL
* Purchase

***

## 5. A guest searches for the best-selling book of a given year, if no year is given, return the best-selling book for the entire history.

Click on `log in as Guest` to log in as Guest. Under **Best selling Book**, enter a year and `submit` to return the result of the best selling of the given year. If no year given, return of all year.

Test Example:
* Guest userID: 1017(Automatically generated)
* Best Selling Book:
* 2021------>9780735219106 	 Where the Crawdads Sing
* 2022------>9781589255517 	 I Love You to the Moon and Back
* All Time(No type)------>9780134763644 	 Calculus

***

## 6. A customer checks their order history and reorder a book

From store main page, click on `My Order` to view all order history.  
Click on `Reorder` to reorder the book (put it into shopping cart)

Test Example:
* Email: LydiaSchwartz@gmail.com
* Password: 1001

***

## 7. An author purchases their own books.

Log in as an author, on the store main page, hit `My Books` to view the books written by this author. Click on `Add to Cart` to add the book to shopping cart and then purchase the book.

Test Example:
* Email: YujinAn@gmail.com
* Password: 1111
* Payment method:
  * Account: 6305912743995640
  * Expire: 09/25
  * CVS: 414

***

## 8. A customer gives rating and comment to a book they have purchased, checks rating and comments of a book.

On store main page, click on `Detail` to give rating and comment (\*Only customers who have purchased the book can comment on it)  
On the bookstore main page, click on `View Rating` to view all of the ratings.

Test Example:
* Email: LydiaSchwartz@gmail.com
* Password: 1001
* Comment:
  * Title: Calculus

***

## Extra Point
1. Publishers can update their address.
2. Superuser can see all the users' prime status and change them
3. Superuser can change the order status
   - Finished
   - Processing
   - Pending (cannot for now because it's individual's shopping cart)
4. Customers can change their information (password, name, email, phone number and address) by clicking the `My Info` button on the store page. (Guest will not be able to change their password)
5. Customer can add new payment method when purchasing books. Customers will need at least one payment method to be able to purchase.
6. Customers can view the best rating book of a given year by entering a year inside `View Rating`; if no year is given, the best rating book of all time will be displayed.
7. Customers can add different books and add more than one book into shopping cart. Customers can delete the entry(books) from shopping cart. In-stock number will change accordingly after customers purchase that particular book. Customers cannot buy books that is out-of-stock.
8. Shopping Cart will show the total price, delivery fee and time
9. Customers can search by ISBN as well
