-- V18 schema for Vercel + Supabase.
-- ВНИМАНИЕ: команда drop table удалит тестовые новости. Если новости уже рабочие, сделайте экспорт перед запуском.

drop table if exists public.news;

create table public.news (
    id bigint generated always as identity primary key,
    title text not null,
    slug text unique,
    short text,
    "full" text,
    excerpt text,
    content text,
    category text,
    image text,
    image2 text,
    image3 text,
    image_url text,
    date date default current_date,
    year integer,
    status text default 'published',
    published boolean default true,
    is_published boolean default true,
    created_at timestamptz default now(),
    updated_at timestamptz default now()
);

alter table public.news enable row level security;

drop policy if exists "public read" on public.news;
drop policy if exists "service write" on public.news;

create policy "public read"
on public.news
for select
using (true);

create policy "service write"
on public.news
for all
using (true)
with check (true);

insert into storage.buckets (id, name, public)
values ('news-images', 'news-images', true)
on conflict (id) do update set public = true;

drop policy if exists "public read news images" on storage.objects;
drop policy if exists "service write news images" on storage.objects;

create policy "public read news images"
on storage.objects
for select
using (bucket_id = 'news-images');

create policy "service write news images"
on storage.objects
for all
using (bucket_id = 'news-images')
with check (bucket_id = 'news-images');
-- V20: таблица направлений для редактирования через админку.
-- Выполнить один раз в Supabase SQL Editor.

create table if not exists public.directions (
    slug text primary key,
    num text,
    title text not null,
    lead text,
    points text,
    image1 text,
    image2 text,
    image3 text,
    caption1 text default 'Материал 1',
    caption2 text default 'Материал 2',
    caption3 text default 'Материал 3',
    sort_order integer default 0,
    updated_at timestamptz default now()
);

alter table public.directions enable row level security;

drop policy if exists "public read directions" on public.directions;
drop policy if exists "service write directions" on public.directions;

create policy "public read directions"
on public.directions
for select
using (true);

create policy "service write directions"
on public.directions
for all
using (true)
with check (true);

insert into public.directions (slug, num, title, lead, points, image1, image2, image3, caption1, caption2, caption3, sort_order) values
('culture','01','Культурные события','Организация и проведение фестивалей, конкурсов, выставок, концертов, творческих встреч и культурно-зрелищных мероприятий.','Разработка программы события и визуальной концепции.
Подготовка площадки, участников и информационного сопровождения.
Фотоотчет, новости и фиксация результатов для публичной отчетности.','assets/img/directions/culture-1.svg','assets/img/directions/culture-2.svg','assets/img/directions/culture-3.svg','Материал 1','Материал 2','Материал 3',1),
('cinema','02','Кино и медиапроекты','Кинопоказы, кинопрограммы, документальные и просветительские форматы, создание видеоматериалов о культурной жизни Забайкалья.','Показы и обсуждения фильмов для разных аудиторий.
Медиасопровождение культурных инициатив.
Развитие интереса к региональной киноиндустрии.','assets/img/directions/cinema-1.svg','assets/img/directions/cinema-2.svg','assets/img/directions/cinema-3.svg','Материал 1','Материал 2','Материал 3',2),
('education','03','Образование и проектная подготовка','Команда проходит обучение, семинары и консультации, чтобы качественно готовить заявки, участвовать в грантовых проектах и представлять их партнерам.','Осваиваем проектную логику: цель, задачи, целевые группы, календарный план и бюджет.
Готовы участвовать в грантовых конкурсах и представлять социально-культурные проекты.
Проводим лекции, мастер-классы и творческие лаборатории в сфере кино, культуры и искусства.','assets/img/directions/education-1.svg','assets/img/directions/education-2.svg','assets/img/directions/education-3.svg','Материал 1','Материал 2','Материал 3',3),
('leisure','04','Культурный досуг','Современные формы досуга для жителей разных возрастов: встречи, клубные форматы, кинопоказы, просветительские программы.','Делаем культурные события доступнее для жителей региона.
Учитываем потребности разных социально-возрастных групп.
Создаем пространство общения, творчества и развития.','assets/img/directions/leisure-1.svg','assets/img/directions/leisure-2.svg','assets/img/directions/leisure-3.svg','Материал 1','Материал 2','Материал 3',4),
('partners','05','Партнерство','Сотрудничество с органами власти, учреждениями культуры, бизнесом, авторами, исполнителями, волонтерами и общественными инициативами.','Министерство культуры Забайкальского края.
ООО «Удоканская медь».
Учреждения культуры, творческие коллективы, авторы, исполнители, волонтерские и добровольческие движения.','assets/img/directions/partners-1.svg','assets/img/directions/partners-2.svg','assets/img/directions/partners-3.svg','Материал 1','Материал 2','Материал 3',5),
('grants','06','Грантовые проекты','Подготовка и реализация социальных, социокультурных, культурных, профессиональных и любительских программ через субсидии и грантовые конкурсы.','Описание актуальности, целевых групп и измеримых результатов.
Сбор публичных подтверждений: новости, фотоотчеты, партнеры, отзывы.
Планирование календаря, бюджета и информационного сопровождения.','assets/img/directions/grants-1.svg','assets/img/directions/grants-2.svg','assets/img/directions/grants-3.svg','Материал 1','Материал 2','Материал 3',6)
on conflict (slug) do nothing;
