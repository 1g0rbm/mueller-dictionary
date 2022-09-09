### dump words table
```bash
docker exec -i pg_container_name /bin/bash -c "PGPASSWORD=password pg_dump --username muellerdict muellerdict --table public.words --no-owner" > ~/mueller_dict_words.sql
```
