UPDATE task
SET task.user_id = (SELECT id FROM user WHERE username = 'anonyme')
WHERE task.user_id IS NULL
