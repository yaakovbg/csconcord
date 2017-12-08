select word,count(word) as word_count from articleword group by word ;

select a.word_count,count(a.word_count) as numOfWords from (select word,count(word) as word_count from articleword group by word)as a group by a.word_count

select a.word_count,count(a.word_count) from (select word,count(word) as word_count from articleword where word in (SELECT word from articlewordgroup where wgid='18')group by word)as a group by a.word_count

SELECT DISTINCT CHAR_LENGTH(word),word from articleword ORDER BY CHAR_LENGTH(word) ASC

SELECT count(a.wordlength),a.wordlength FROM (SELECT DISTINCT CHAR_LENGTH(word) as wordlength,word from articleword ) as a group by a.wordlength

select letter,count(letter) as letter_count from articleletter group by letter  
ORDER BY `articleletter`.`letter`  DESC;