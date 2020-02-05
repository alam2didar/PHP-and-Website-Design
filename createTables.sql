
create table  Member(
	Email varchar(100) primary key,
	passwd varchar(100) not null,
	FirstName char(50),
    LastName char(50)
);

create table  Company(
	CoNumber integer primary key,
	CavgRating decimal	
);

create table  Items(
	Title varchar(50) not null,
	Type char(10) not null,
	Description text not null,
	mark varchar(100) not null,
	possession varchar(100) not null,
	constraint primary key(Title,Type,mark),
	constraint FK_mark foreign key(mark) references Member(Email) on delete no action on update cascade,
	constraint FK_possession foreign key(possession) references Member(Email) on delete no action on update cascade
);


create table  Midshipmen(
	Email varchar(100) primary key,
	class integer not null,
	CoNumber integer not null,
	constraint FK_MID foreign key(Email) references Member(Email) on delete cascade,
    constraint FK_midCompany foreign key(CoNumber) references Company(CoNumber) on delete cascade
);

create table  SEL(
	Email varchar(100) ,
	rate varchar(50), 
	CoNumber integer not null unique,
	constraint FK_SEL foreign key(Email) references Member(Email) on delete cascade,
	constraint FK_selCompany foreign key(CoNumber) references Company(CoNumber) on delete cascade,
	primary key(Email)
);

create table  CO(
	Email varchar(100),
	rank varchar(40) not null,
	warfare varchar(40),
	CoNumber integer not null unique,
	constraint FK_CO foreign key(Email) references Member(Email) on delete cascade,
	constraint FK_coCompany foreign key(CoNumber) references Company(CoNumber) on delete cascade,
	primary key(Email)
);



create table  Messages(
	messageID integer primary key AUTO_INCREMENT,
	msgType enum('nice', 'mean'),
	dateReceived date not null,
	Message text null,
	SenderEmail varchar(100) not null,
	ReceiverEmail varchar(100) not null,
	constraint FK_recipient foreign key (SenderEmail) references Member(Email) on delete no action on update cascade,
	constraint FK_givenby foreign key (ReceiverEmail) references Member(Email) on delete no action on update cascade 
);

create table  Exchanges(
    ExchangeID integer primary key AUTO_INCREMENT,
	Title varchar (50) not null,
	Type char(10) not null,
	mark varchar(100) not null,
	Lender varchar(100) not null,
	Borrower varchar(100) not null,
	ExchangeDate date not null,
	constraint FK_Lender foreign key (Lender) references Member(Email) on delete no action on update cascade,
	constraint FK_Borrower foreign key (Borrower) references Member(Email) on delete no action on update cascade,
	constraint FK_Owner foreign key (Title, Type, mark) references Items(Title, Type, mark) on delete no action on update cascade
);

-- BeingRated can either be the lender or borrower, enforced by rateCheck Trigger
create table  Ratings(
	exchangeID integer not null,
	BeingRated varchar (100) not null,
	Rater varchar (100) not null,
	score integer not null,
	comments text null,
	primary key(exchangeID, BeingRated, Rater),
	constraint FK_exID foreign key(exchangeID) references Exchanges(exchangeID) on delete no action on update cascade
);

-- Notifications table
create table Notifications(
	notificationID integer primary key AUTO_INCREMENT,
	target varchar(100) not null,
	borrower varchar(100) null,
	itemName varchar(50) null,
	notificationType enum('rating', 'exchange') not null,
	message varchar(100) not null,
	dateCreated date not null,
	constraint FK_target foreign key(target) references Member(Email) on delete no action on update cascade
);
