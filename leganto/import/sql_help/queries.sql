--- Vrati uzivatele, kteri maji duplicitni e-mail
SELECT reader_user.*, COUNT(opinion.id) AS opinions
FROM reader_user
LEFT JOIN opinion ON reader_user.id = opinion.user
LEFT JOIN
WHERE reader_user.email IN(SELECT email FROM reader_user GROUP BY email HAVING COUNT(email) > 1) GROUP BY reader_user.id