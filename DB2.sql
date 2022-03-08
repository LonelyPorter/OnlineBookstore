-- usage: mysql -u root bookstore < DB2.sql

-- Drop table if exists
SET FOREIGN_KEY_CHECKS=0;

drop table if exists Customers;
drop table if exists Books;
drop table if exists Publisher;
drop table if exists Delivery;
drop table if exists `Order`;
drop table if exists Member;
drop table if exists Guest;
drop table if exists Author;
drop table if exists `Write`;
drop table if exists InOrder;
drop table if exists Rating;
drop table if exists Payment;
drop table if exists ShoppingCart;
drop table if exists InCart;

SET FOREIGN_KEY_CHECKS=1;
-- Create table
create table Publisher (
  name varchar(20) primary key,
  address varchar(100)
);

INSERT INTO `Publisher` (`name`, `address`) VALUES
('Akashic Books', 'Brooklyn, NY'),
('Dzanc Books', 'Detroit, MI'),
('Graywolf Press', 'Minneapolis, MN'),
('Hanging Loose Press', 'Brooklyn, NY'),
('McSweeney''s', 'San Francisco, CA'),
('Pearson', '1330 Avenue of the Americas New York, NY 10019 United States');

create table Delivery (
  method varchar(10) primary key,
  time int,
  cost real
);

INSERT INTO Delivery (method, time, cost) VALUES
('email', 0, 0),
('free', 6, 0),
('hardcover', 6, 5.99),
('loose leaf', 4, 2.99),
('paperback', 5, 4.99);

create table Customers (
  userID int NOT NULL AUTO_INCREMENT primary key,
  password varchar(20),
  name varchar(30),
  email varchar(40) unique,
  phone char(10),
  address varchar(50)
);

INSERT INTO `Customers` (`userID`, `password`, `name`, `email`, `phone`, `address`) VALUES
(1000, '1000', 'Tina Maldonado', 'TinaMaldonado@gmail.com', '2317432154', '8532 James Ave, Hopkinsville, KY 42240'),
(1001, '1001', 'Lydia Schwartz', 'LydiaSchwartz@gmail.com', '5088655419', '424 Gonzales Court, Brooklyn, NY 11201'),
(1002, '1002', 'Ollie Collier', 'OllieCollier@gmail.com', '8326399033', '7334 South Sunset Drive, Fayetteville, NC 28303'),
(1003, '1003', 'Rick Pittman', 'RickPittman@gmail.com', '2102825113', '894 Edgewater Street, Orland Park, IL 60462'),
(1004, '1004', 'Lynette Curry', 'LynetteCurry@gmail.com', '2343159641', '7650 Golf Street, Asheville, NC 28803'),
(1005, '1005', 'Lester Ball', 'LesterBall@gmail.com', '2076906874', '137 Nichols Street, Loxahatchee, FL 33470'),
(1006, '1006', 'Felix Daniel', 'FelixDaniel@gmail.com', '7472328875', '58 N. Charles Street, Bensalem, PA 19020'),
(1007, '1007', 'Rita Swanson', 'RitaSwanson@gmail.com', '2674049353', '8910 Ocean Ave, Hempstead, NY 11550'),
(1008, '1008', 'Ernesto Robbins', 'ErnestoRobbins@gmail.com', '4233417279', '8183 Coffee St, Derby, KS 67037'),
(1009, '1009', 'Jared Terry', 'JaredTerry@gmail.com', '4049571280', '2 Glenlake Street, Shelton, CT 06484'),
(1010, '1010', 'Clara Farmer', 'ClaraFarmer@gmail.com', '2122009498', '54 Elmwood Street, Andover, MA 01810'),
(1011, '1011', 'Erik Saunders', 'ErikSaunders@gmail.com', '5512225044', '47 East Bridle St, Harrison Township, MI 48045'),
(1012, '1012', 'Maggie Townsend', 'MaggieTownsend@gmail.com', '2174634839', '780 Primrose Ave, Stoughton, MA 02072'),
(1013, '1013', 'Danny Higgins', 'DannyHiggins@gmail.com', '5105258777', '93 Gulf Street, North Tonawanda, NY 14120'),
(1014, '1014', 'Ramon Mckinney', 'RamonMckinney@gmail.com', '4257072409', '138 53rd Dr, Powder Springs, GA 30127'),
(1015, '1015', 'Shirley Bryant', 'ShirleyBryant@gmail.com', '6144802118', '39 Rock Creek Circle, Dalton, GA 30721');

create table Books (
  ISBN char(13) primary key,
  title varchar(50),
  type varchar(10),
  price real,
  Category varchar(20),
  in_stock int,
  pName varchar(20),
  method varchar(10),

  constraint foreign key (pName) references Publisher(name),
  constraint foreign key (method) references Delivery(method)
);

INSERT INTO `Books` (`ISBN`, `title`, `type`, `price`, `Category`, `in_stock`, `pName`, `method`) VALUES
('9780134763644', 'Calculus', 'hardcover', 109.55, 'Mathematics', 15, 'Pearson', 'hardcover'),
('9780593230572', 'The 1619 Project: A New Origin Story', 'audio', 30.63, 'History', -1, 'Hanging Loose Press', 'email'),
('9780735219106', 'Where the Crawdads Sing', 'paperback', 9.98, 'Genre Fiction', 8, 'Dzanc Books', 'paperback'),
('9781284194531', 'Ugly''s Electrical References, 2020 Edition', 'eletronic', 13.58, 'Engineering', -1, 'Akashic Books', 'email'),
('9781454891536', 'Property (Examples & Explanations)', 'paperback', 59.99, 'Business', 12, 'Graywolf Press', 'paperback');

create table `Order` (
  Number varchar(20) primary key,
  time Date,
  status varchar(10),
  userID int,

  constraint foreign key (userID) references Customers(userID)
);

INSERT INTO `Order` (`Number`, `time`, `status`, `userID`) VALUES
('369A4545U8', '2022-02-17', 'pending', 1003),
('5496C9V939', '2022-02-15', 'processing', 1008),
('723F68G592', '2022-01-15', 'pending', 1005),
('72748837PT', '2022-02-16', 'processing', 1009),
('788727W426', '2021-01-01', 'finished', 1010),
('854T523666', '2022-02-01', 'pending', 1004),
('93347467GJ', '2021-04-05', 'pending', 1007),
('L738X73953', '2021-03-09', 'pending', 1006);

create table Member (
  userID int primary key,
  prime boolean,

  constraint foreign key (userID) references Customers(userID)
);

INSERT INTO `Member` (`userID`, `prime`) VALUES
(1001, 0),
(1002, 0),
(1003, 0),
(1004, 1),
(1005, 0);

create table Guest (
  userID int primary key,

  constraint foreign key (userID) references Customers(userID)
);

INSERT INTO `Guest` (`userID`) VALUES
(1011),
(1012),
(1013),
(1014),
(1015);

create table Author (
  userID int primary key,
  prime boolean,

  constraint foreign key (userID) references Customers(userID)
);

INSERT INTO `Author` (`userID`, `prime`) VALUES
(1006, 0),
(1007, 0),
(1008, 0),
(1009, 0),
(1010, 1);

create table `Write` (
  ISBN char(13),
  userID int,
  primary key (ISBN, userID),

  constraint foreign key (ISBN) references Books(ISBN),
  constraint foreign key (userID) references Author(userID)
);

INSERT INTO `Write` (`ISBN`, `userID`) VALUES
('9780134763644', 1006),
('9780593230572', 1008),
('9780735219106', 1007),
('9781284194531', 1010),
('9781454891536', 1009);

create table InOrder (
  ISBN char(13),
  orderNumber varchar(20),
  quantity int,
  primary key(ISBN, orderNumber),

  constraint foreign key (ISBN) references Books(ISBN),
  constraint foreign key (orderNumber) references `Order`(`Number`)
);

INSERT INTO `InOrder` (`ISBN`, `orderNumber`, `quantity`) VALUES
('9780134763644', '788727W426', 1),
('9780593230572', '5496C9V939', '1'),
('9780735219106', '72748837PT', '1'),
('9781284194531', '788727W426', '1'),
('9781454891536', '72748837PT', '1');

create table Rating (
  userID int,
  Number int,
  ISBN char(13),
  star int,
  comment varchar(200),
  time Date,
  primary key (userID, `Number`, ISBN),

  constraint foreign key (userID) references Customers(userID)
);

INSERT INTO `Rating` (`userID`, `Number`, `ISBN`, `star`, `comment`, `time`) VALUES
(1001, 1, '9780134763644', 3, 'good', '2022-01-13'),
(1002, 2, '9780134763645', 4, 'great', '2022-01-19'),
(1003, 3, '9780134763646', 3, 'good', '2022-01-25'),
(1004, 4, '9780134763647', 5, 'excellent', '2022-02-25'),
(1005, 5, '9780134763648', 1, 'very bad', '2022-02-28');

create table Payment(
  userID int,
  Account char(16),
  expire char(5),
  cvs int,
  primary key (userID, Account),

  constraint foreign key (userID) references Customers(userID)
);

INSERT INTO `Payment` (`userID`, `Account`, `expire`, `cvs`) VALUES
(1001, '4556974849415440', ' 3/23', 453),
(1004, '2301229546494990', ' 11/2', 425),
(1007, '4532870842357070', ' 10/2', 128),
(1010, '4716005143328930', ' 02/2', 753),
(1013, '4312584639349740', ' 05/2', 356);

create table ShoppingCart (
  ID int NOT NULL AUTO_INCREMENT,
  orderNumber varchar(20),
  userID int,
  primary key (ID, orderNumber),

  constraint foreign key (orderNumber) references `Order`(`Number`),
  constraint foreign key (userID) references Customers(userID)
);

INSERT INTO `ShoppingCart` (`ID`, `orderNumber`, `userID`) VALUES
(1, '369A4545U8', 1003),
(2, '854T523666', 1004),
(3, '723F68G592', 1005),
(4, 'L738X73953', 1006),
(5, '93347467GJ', 1007);

create table InCart (
  ISBN char(13),
  cartID int,
  cartOrder varchar(20),
  quantity int,
  primary key (ISBN, cartID, cartOrder),

  constraint foreign key (ISBN) references Books(ISBN),
  constraint foreign key (cartID) references ShoppingCart(ID),
  constraint foreign key (cartOrder) references ShoppingCart(orderNumber)
);

INSERT INTO `InCart` (`ISBN`, `cartID`, `cartOrder`, `quantity`) VALUES
('9780134763644', 1, '369A4545U8', 1),
('9780593230572', 3, '723F68G592', 1),
('9780735219106', 2, '854T523666', 1),
('9781284194531', 5, '93347467GJ', 1),
('9781454891536', 4, 'L738X73953', 1);
