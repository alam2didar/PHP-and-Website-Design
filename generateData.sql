insert into  Member(Email, passwd, FirstName, LastName)
	values('m156312@usna.edu', '872e5638035c6c3148307793b503106f86d8f78e', 'Matthew', 'Sommers'),
	('m152490@usna.edu', '8f7539f1944922c5b7e35649aaae2ab8768e0d8e', 'Ethan', 'Genco'),
	('rees@usna.edu', '8f7539f1944922c5b7e35649aaae2ab8768e0d8e', 'Christina', 'Rees'),
	('tarr@usna.edu', '8f7539f1944922c5b7e35649aaae2ab8768e0d8e', 'Kenneth', 'Tarr'),
	('kimmanee@usna.edu', '8f7539f1944922c5b7e35649aaae2ab8768e0d8e', 'Tony', 'Kimmanee'),
	('campbell@usna.edu', '8f7539f1944922c5b7e35649aaae2ab8768e0d8e', 'Preston', 'Campbell'),
	('m150084@usna.edu', '918d5b50d4e0c859548964a9e5973e0289a390a8', 'Didar', 'Alam');

insert into  Company(CoNumber, CavgRating)
	values(2, 5.0),
	(4, 0.5);

insert into  Items(Title, Type, description, mark, possession)
	values('HarryPotter', 'book', 'page length: 435', 'm156312@usna.edu', 'm156312@usna.edu'),
 ('Bourne', 'dvd', 'duration: 144', 'm152490@usna.edu', 'm152490@usna.edu'),
('Flight', 'dvd', 'duration: 130', 'm150084@usna.edu', 'm150084@usna.edu');

insert into  Midshipmen(Email, class, CoNumber)
	values('m156312@usna.edu', 2015, 2),
	('m152490@usna.edu', 2015, 4),
	('m150084@usna.edu', 2015, 2);

insert into  SEL(Email, rate, CoNumber)
	values('rees@usna.edu', 'CTTC', 2),
	('kimmanee@usna.edu', 'GySGT', 4);

insert into  CO(Email, rank, warfare, CoNumber)
	values('tarr@usna.edu', 'CAPT', 'Marine Corps', 2),
	('campbell@usna.edu', 'LT', 'Navy', 4);

/*
-- Sit1:Alam84 and Genco90 were friends more than 30 days ago, Alam90 and Sommers12 are
--       friends now, so long as Alam is the owner 
insert into  Messages(msgType, dateReceived, Message, SenderEmail, ReceiverEmail)
values('nice', '2014-1-1', 'Alam to Genco1', 'm150084@usna.edu', 'm152490@usna.edu'),
	('nice', '2014-1-1', 'Alam to Genco2', 'm150084@usna.edu', 'm152490@usna.edu'),
	('nice', '2014-1-1', 'Alam to Genco3', 'm150084@usna.edu', 'm152490@usna.edu'),
	('nice', '2014-1-1', 'Genco to Alam1', 'm152490@usna.edu', 'm150084@usna.edu'),
	('nice', '2014-1-1', 'Genco to Alam2', 'm152490@usna.edu', 'm150084@usna.edu'),
	('nice', '2014-1-1', 'Genco to Alam3', 'm152490@usna.edu', 'm150084@usna.edu'),
	('nice', '2014-4-1', 'Alam to Sommers1', 'm150084@usna.edu', 'm156312@usna.edu'),
	('nice', '2014-4-1', 'Alam to Sommers2', 'm150084@usna.edu', 'm156312@usna.edu'),
	('nice', '2014-4-1', 'Alam to Sommers3', 'm150084@usna.edu', 'm156312@usna.edu'),
	('nice', '2014-4-1', 'Sommers to Alam', 'm156312@usna.edu', 'm150084@usna.edu');

-- Sit2: Genco90 and Alam84 have 50-50 mean and nice messages recently, 
--       Alam90 and Sommers12 are friends now, so long as Alam is owner
insert into  Messages(msgType, dateReceived, Message, SenderEmail, ReceiverEmail)
values('nice', '2014-4-1', 'Alam to Genco1', 'm150084@usna.edu', 'm152490@usna.edu'),
	('nice', '2014-4-1', 'Alam to Genco2', 'm150084@usna.edu', 'm152490@usna.edu'),
	('mean', '2014-4-1', 'Alam to Genco3', 'm150084@usna.edu', 'm152490@usna.edu'),
    ('mean', '2014-4-1', 'Alam to Genco4', 'm150084@usna.edu', 'm152490@usna.edu'),
	('nice', '2014-4-1', 'Genco to Alam1', 'm152490@usna.edu', 'm150084@usna.edu'),
	('nice', '2014-4-1', 'Genco to Alam2', 'm152490@usna.edu', 'm150084@usna.edu'),
	('mean', '2014-4-1', 'Genco to Alam3', 'm152490@usna.edu', 'm150084@usna.edu'),
	('mean', '2014-4-1', 'Genco to Alam4', 'm152490@usna.edu', 'm150084@usna.edu'),
	('mean', '2014-4-1', 'Genco to Alam4', 'm152490@usna.edu', 'm150084@usna.edu'),
	('nice', '2014-4-1', 'Alam to Sommers1', 'm150084@usna.edu', 'm156312@usna.edu'),
	('nice', '2014-4-1', 'Alam to Sommers2', 'm150084@usna.edu', 'm156312@usna.edu'),
	('nice', '2014-4-1', 'Alam to Sommers3', 'm150084@usna.edu', 'm156312@usna.edu'),
	('nice', '2014-4-1', 'Sommers to Alam', 'm156312@usna.edu', 'm150084@usna.edu');

-- Sit3: no one is friends with each other
--       try it by too many mean, not recent enough, and no enough messages
insert into  Messages(msgType, dateReceived, Message, SenderEmail, ReceiverEmail)
values('nice', '2014-1-1', 'Alam to Genco1', 'm150084@usna.edu', 'm152490@usna.edu'),
	('nice', '2014-1-1', 'Alam to Genco2', 'm150084@usna.edu', 'm152490@usna.edu'),
	('nice', '2014-1-1', 'Alam to Genco3', 'm150084@usna.edu', 'm152490@usna.edu'),
	('nice', '2014-1-1', 'Genco to Alam1', 'm152490@usna.edu', 'm150084@usna.edu'),
	('nice', '2014-1-1', 'Genco to Alam2', 'm152490@usna.edu', 'm150084@usna.edu'),
	('nice', '2014-1-1', 'Genco to Alam3', 'm152490@usna.edu', 'm150084@usna.edu'),
	('nice', '2014-4-1', 'Alam to Sommers1', 'm150084@usna.edu', 'm156312@usna.edu'),
	('nice', '2014-4-1', 'Alam to Sommers2', 'm150084@usna.edu', 'm156312@usna.edu'),
	('nice', '2014-4-1', 'Alam to Sommers3', 'm150084@usna.edu', 'm156312@usna.edu'),
	('mean', '2014-4-1', 'Sommers to Alam', 'm156312@usna.edu', 'm150084@usna.edu');
*/
-- Sit4: Alam and Genco are friends either way, Alam and Sommers are friends either way

insert into  Messages(msgType, dateReceived, Message, SenderEmail, ReceiverEmail)
values('nice', '2014-4-1', 'Alam to Genco1', 'm150084@usna.edu', 'm152490@usna.edu'),
	('nice', '2014-4-1', 'Alam to Genco2', 'm150084@usna.edu', 'm152490@usna.edu'),
	('nice', '2014-4-1', 'Alam to Genco3', 'm150084@usna.edu', 'm152490@usna.edu'),
	('nice', '2014-4-1', 'Genco to Alam1', 'm152490@usna.edu', 'm150084@usna.edu'),
	('nice', '2014-4-1', 'Genco to Alam2', 'm152490@usna.edu', 'm150084@usna.edu'),
	('nice', '2014-4-1', 'Genco to Alam3', 'm152490@usna.edu', 'm150084@usna.edu'),
	('nice', '2014-4-1', 'Alam to Sommers1', 'm150084@usna.edu', 'm156312@usna.edu'),
	('nice', '2014-4-1', 'Alam to Sommers2', 'm150084@usna.edu', 'm156312@usna.edu'),
	('nice', '2014-4-1', 'Alam to Sommers3', 'm150084@usna.edu', 'm156312@usna.edu'),
	('nice', '2014-4-1', 'Sommers to Alam1', 'm156312@usna.edu', 'm150084@usna.edu'),
	('nice', '2014-4-1', 'Sommers to Alam2', 'm156312@usna.edu', 'm150084@usna.edu'),
	('nice', '2014-4-1', 'Sommers to Alam3', 'm156312@usna.edu', 'm150084@usna.edu'),
	('nice', '2014-4-1', 'Sommers to Alam4', 'm156312@usna.edu', 'm150084@usna.edu');


-- Alam allows Genco to lend his item,
-- Alam allows Sommers to lend his item,
-- Sommers allows Alam to lend his item,
-- Genco allows Alam to lend his item
insert into  Exchanges(Title, Type, mark, Lender, Borrower, ExchangeDate)
values('Flight', 'dvd', 'm150084@usna.edu', 'm150084@usna.edu','m152490@usna.edu', '2014-4-13'),
('Flight', 'dvd', 'm150084@usna.edu', 'm152490@usna.edu','m156312@usna.edu', '2014-4-14'),
('HarryPotter', 'book', 'm156312@usna.edu', 'm156312@usna.edu','m150084@usna.edu', '2014-4-13'),
('Bourne', 'dvd', 'm152490@usna.edu', 'm152490@usna.edu','m150084@usna.edu', '2014-4-13');

 insert into  Ratings(exchangeID, BeingRated, Rater, score, comments)
 values(3, 'm150084@usna.edu', 'm156312@usna.edu', 2,'Returned late'), 
	(2, 'm152490@usna.edu', 'm156312@usna.edu', 2,'Good Movie'),
	(4, 'm150084@usna.edu', 'm152490@usna.edu', 2,'Returned scratched');

select * from exchanges;
select * from items;

select * from ratings;

