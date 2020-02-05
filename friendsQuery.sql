
-- Query takes in a variable email and returns the emails of who he's friends with
-- IN THIS CASE, m150084@usna.edu is the variable, replace that with your PHP variable email
-- Note that Rcount makes sure the user sent 10 messages and Scount makes sure he received 5
-- ^ you can make these numbers smaller for testing purposes so you don't have to make 100's of messages
SELECT distinct Email
FROM 
	member,
	(Select distinct ReceiverEmail as re, count(messageID) as Rcount 
		from messages 
		where SenderEmail='m150084@usna.edu' AND DATEDIFF(NOW(),dateReceived) <= 30
		Group by ReceiverEmail 
		having Rcount > 9) as recv,

	(Select distinct SenderEmail as se, count(messageID) as Scount 
		from messages 
		where ReceiverEmail='m150084@usna.edu' AND DATEDIFF(NOW(),dateReceived) <= 30 
		Group by SenderEmail 
		having Scount > 4) as sndr,

	(Select distinct ReceiverEmail as nre, count(messageID) as nRcount 
		from messages 
		where SenderEmail='m150084@usna.edu' AND DATEDIFF(NOW(),dateReceived) <= 30 and msgType='nice' 
		Group by ReceiverEmail) as nrecv,

	(Select distinct SenderEmail as nre, count(messageID) as nScount 
		from messages 
		where ReceiverEmail='m150084@usna.edu' AND DATEDIFF(NOW(),dateReceived) <= 30 and msgType='nice' 
		Group by SenderEmail) as nsndr

WHERE re=se AND Email=re AND Email=se AND nRcount/Rcount > 0.9 AND nScount/Scount > 0.9;


-- ---------------TEST Queries for each SELECT STATEMENT nested in the FROM
-- counts receivers more than 2
-- Select distinct ReceiverEmail as re, count(messageID) as Rcount from messages where SenderEmail='m150084@usna.edu' Group by ReceiverEmail having Rcount > 2;

-- counts senders more than 3
-- Select distinct SenderEmail as se, count(messageID) as Scount from messages where ReceiverEmail='m150084@usna.edu' Group by SenderEmail having Scount > 3;

-- count nice recv
-- Select distinct ReceiverEmail as nre, count(messageID) as countNiceRecv from messages where SenderEmail='m150084@usna.edu' and msgType='nice' Group by ReceiverEmail;

-- count nice sendr
-- Select distinct SenderEmail as nre, count(messageID) as countNiceSend from messages where ReceiverEmail='m150084@usna.edu' and msgType='nice' Group by SenderEmail;



