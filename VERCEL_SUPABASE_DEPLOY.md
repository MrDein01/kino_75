# Деплой V18 на Vercel + Supabase

1. В Supabase откройте SQL Editor и выполните файл `SUPABASE_SETUP.sql`.
2. В Vercel проверьте переменные окружения:
   - `SUPABASE_URL`
   - `SUPABASE_SERVICE_ROLE_KEY`
   - `SUPABASE_STORAGE_BUCKET=news-images`
   - `MAX_URL` — если есть ссылка на MAX.
3. В CMD:

```cmd
cd C:\laragon\www\kultura75_site
vercel env pull .env.local
vercel --prod
```

## Новости с несколькими фото

В админке можно загрузить:
- основное фото;
- дополнительное фото 1;
- дополнительное фото 2.

На странице новости они листаются стрелками и автоматически каждые 25 секунд.
