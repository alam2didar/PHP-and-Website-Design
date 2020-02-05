-----------------------------------------------------------------------------------------------------------------------
--  Trigger Page, Trigger Testing can be viewed in liberato/triggers.sql and liberato/generateDate.sql
-----------------------------------------------------------------------------------------------------------------------

/*This trigger prevents you to enter more than 30 companies*/
/* change default delimiter to $$*/ 
DELIMITER $$ 
 -- drop trigger CheckCoCount
/* create trigger: provide trigger name and specify when trigger should be invoked */ 
CREATE TRIGGER  CheckCoCount AFTER INSERT ON  Company
FOR EACH ROW 
BEGIN 
 DECLARE companyCount int; 
 DECLARE msg char(100); 
 
 set companyCount = (select count(CoNumber) from  Company); 
  
 IF (companyCount > 30) THEN 
  insert ignore into  Company(CoNumber, CavgRating)
	values(null, null);
END IF; 
END; $$ 
 
DELIMITER ;
-----------------------------------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------------------------------
-- drop trigger  allowBorrow;
DELIMITER $$ 
-- create trigger: provide trigger name and specify when trigger should be invoked  
CREATE TRIGGER  allowBorrow BEFORE INSERT ON  Exchanges
FOR EACH ROW 
BEGIN 
	/*Lender L is considered a friend of owner O if in the last 30 days O sent at least 10 
	 messages to L, L sent at least 5 messages to O, and for both directions at least 90% 
	 of the messages were “nice”*/
	DECLARE OtoLCount int;
	DECLARE LtoOCount int;
	DECLARE niceOCount int;
	DECLARE niceLCount int;
	-- Total of messages sent from OWNER to LENDER w/in 30 days
	set OtoLCount = (SELECT COUNT(messageID) FROM  Messages WHERE 
DATEDIFF(NOW(),dateReceived) <= 30 AND SenderEmail=new.mark AND ReceiverEmail=new.Lender);

	-- Total of messages sent from LENDER to OWNER w/in 30 days
	set LtoOCount=(SELECT COUNT(messageID) FROM  Messages WHERE 
DATEDIFF(NOW(),dateReceived) <= 30 AND SenderEmail=new.Lender AND ReceiverEmail=new.mark);

	-- Total of NICE messages sent from OWNER to LENDER w/in 30 days
	set niceOCount=(SELECT COUNT(messageID) FROM  Messages WHERE 
DATEDIFF(NOW(),dateReceived) <= 30 AND SenderEmail=new.mark AND ReceiverEmail=new.Lender AND msgType='nice');

	-- Total of NICE messages sent from LENDER to OWNER w/in 30 days
	set niceLCount=(SELECT COUNT(messageID) FROM  Messages WHERE 
DATEDIFF(NOW(),dateReceived) <= 30 AND SenderEmail=new.Lender AND ReceiverEmail=new.mark AND msgType='nice');
	
	-- See if owner of the item is the lender, if not check if they're friends
	-- Also check the amount of messages that were nice
	IF (new.mark!=new.Lender) THEN 
		-- NESTED IF: check amount of messages sent either way
		IF (OtoLCount<10 OR LtoOCount<5 OR ((niceOCount/OtoLCount)<0.9) OR ((niceLCount/LtoOCount)<0.9)) THEN
			set new.Type=null;
			set new.mark=null;
			set new.Lender=null;
			set new.Borrower=null;
		END IF;
	END IF; 
END; $$ 
DELIMITER ;

-----------------------------------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------------------------------
/*-- drop trigger checkRate;
DELIMITER $$ 
-- create trigger: provide trigger name and specify when trigger should be invoked  
CREATE TRIGGER  checkRate BEFORE INSERT ON Ratings
FOR EACH ROW 
BEGIN 
	/*We must check if the BeingRated email is either the Lender or Borrower in exchanges. Also,
	  the rating must be submitted within 14 days of the exchange
	DECLARE exDate date;
	DECLARE vLender varchar(100);
	DECLARE vBorrower varchar(100);

	-- Day Exchange was made
	set exDate = (SELECT ExchangeDate From Exchanges e WHERE new.exchangeID=e.ExchangeID);

	-- Lender in the Exchange
	set vLender=(SELECT Lender From Exchanges e WHERE new.exchangeID=e.ExchangeID);

	-- Borrower in the Exchange
	set vBorrower=(SELECT Borrower From Exchanges e WHERE new.exchangeID=e.ExchangeID);
	
	-- See if BeingRated is Borrower or Lender, also if today is within 14 day limit
	 IF (new.BeingRated != vLender and new.BeingRated != vBorrower) or DATEDIFF(NOW(),exDate) > 14 THEN 
		set new.exchangeID = null;
	 END IF; 
END; $$ 
DELIMITER ;
*/
-----------------------------------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------------------------------
-- drop trigger updatePossession;
DELIMITER $$ 
-- create trigger: provide trigger name and specify when trigger should be invoked  
CREATE TRIGGER  updatePossession AFTER INSERT ON Exchanges
FOR EACH ROW
BEGIN 
	/*Update who has possession of an item*/
	UPDATE Items SET possession = new.Borrower
	WHERE Title = new.Title and Type = new.Type and mark = new.mark;

	-- Create a notification that the owner's item was exchanged
	insert into Notifications (target, itemName, borrower, notificationType, message, dateCreated)
			values (new.mark, new.Title, new.Borrower, 'exchange', 'has been given to', now() );
END; $$ 
DELIMITER ;


-----------------------------------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------------------------------
-- drop trigger ratingDropNotification_checkRate;
DELIMITER $$ 
-- create trigger: provide trigger name and specify when trigger should be invoked  
CREATE TRIGGER  ratingDropNotification_checkRate BEFORE INSERT ON Ratings
FOR EACH ROW 
BEGIN 
	/*We must check if the BeingRatedAverage has dropped below two stars*/
	DECLARE oldScore integer;
	DECLARE oldRating integer;
	DECLARE newRating integer;
	DECLARE numRatings integer;

	DECLARE exDate date;
	DECLARE vLender varchar(100);
	DECLARE vBorrower varchar(100);

	-- number of ratings for member
	set numRatings = (SELECT count(BeingRated) From Ratings r WHERE new.BeingRated=r.BeingRated);

	-- sum of total stars for member
	set oldScore=(SELECT sum(score) FROM Ratings r WHERE new.BeingRated=r.BeingRated);

	-- member's old rating
	set oldRating = oldScore/numRatings;
	-- member's new rating
	set newRating = (oldScore + new.score)/(numRatings + 1);
	
	-- See if rating has dropped below two stars
	 IF (oldRating > 2 and newRating <= 2) THEN
		insert into Notifications (target, notificationType, message, dateCreated)
			values (new.BeingRated, 'rating', 'rating has dropped below two stars', now() );
	 END IF; 

	/*We must check if the BeingRated email is either the Lender or Borrower in exchanges. Also,
	  the rating must be submitted within 14 days of the exchange*/


	-- Day Exchange was made
	set exDate = (SELECT ExchangeDate From Exchanges e WHERE new.exchangeID=e.ExchangeID);

	-- Lender in the Exchange
	set vLender=(SELECT Lender From Exchanges e WHERE new.exchangeID=e.ExchangeID);

	-- Borrower in the Exchange
	set vBorrower=(SELECT Borrower From Exchanges e WHERE new.exchangeID=e.ExchangeID);
	
	-- See if BeingRated is Borrower or Lender, also if today is within 14 day limit
	 IF (new.BeingRated != vLender and new.BeingRated != vBorrower) or (DATEDIFF(NOW(),exDate) > 14) THEN 
		set new.exchangeID = null;
	 END IF; 
END; $$ 
DELIMITER ;