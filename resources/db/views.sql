-- POHLEDY NA DATA V DATABAZI
-- ------------------------------------

DROP VIEW IF EXISTS view_book_title;
CREATE VIEW view_book_title AS
	SELECT
		book_title.id_book AS id_book,
		book_title.title AS book_title,
		book_title.subtitle AS book_subtitle,
		serie.id_serie AS id_serie,
		serie_name.name AS serie_name
	FROM book_title
	INNER JOIN book USING(id_book)
	LEFT JOIN serie USING(id_serie)
	LEFT JOIN serie_name USING(id_serie, id_language)
;

