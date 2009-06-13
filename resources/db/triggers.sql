-- POHLEDY NA DATA V DATABAZI
-- ------------------------------------
DELIMITER $$

-- Pokud je vkladan novy lokalizovany titul bez knihy, vytvori knihu
DROP TRIGGER IF EXISTS trigger_book_title_insert $$
CREATE TRIGGER trigger_book_title_insert

	BEFORE INSERT ON book_title FOR EACH ROW
	
	BEGIN
		DECLARE last_book_id INT UNSIGNED;
		IF NEW.id_book = NULL THEN
			INSERT INTO book VALUES(id_serie = NULL);
			SELECT MAX(id_book) INTO last_book_id FROM book;
			SET NEW.id_book = last_book_id;
		END IF;
	END;
	
DELIMITER $$
-- Pokud je vkladana nova lokalizovana serie bez obecne serie, vytvori ji
DROP TRIGGER IF EXISTS trigger_serie_name_insert $$
CREATE TRIGGER trigger_serie_name_insert

	BEFORE INSERT ON serie_name FOR EACH ROW
	
	BEGIN
		DECLARE last_serie_id INT UNSIGNED;
		IF NEW.id_serie = NULL THEN
			INSERT INTO serie VALUES(id_serie = 0);
			SELECT MAX(id_serie) INTO last_serie_id FROM serie;
			SET NEW.id_serie = last_serie_id;
		END IF;
	END;
