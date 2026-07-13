<?php
require_once __DIR__ . '/functions.php';

function seed_news_items() {
    return [
        [
            'id' => 1,
            'slug' => 'dobroe-lukoshko-2025',
            'title' => 'Проект «Доброе лукошко» получил поддержку в 2025 году',
            'short' => 'АНО «Культура 75» реализует культурные и социально значимые инициативы для жителей Забайкальского края.',
            'full' => "В 2025 году организация получила целевую субсидию на реализацию проекта «Доброе лукошко» в рамках государственной программы Забайкальского края «Развитие культуры в Забайкальском крае».\n\nНа сайте эту новость можно заменить на подробный отчет: добавить фотографии, количественные показатели, партнеров, географию и отзывы участников.",
            'date' => '2025-10-13',
            'category' => 'Проекты 2025',
            'image' => 'assets/img/directions/culture-1.svg',
            'image2' => 'assets/img/directions/culture-2.svg',
            'image3' => 'assets/img/directions/culture-3.svg',
            'published' => true,
            'created_at' => date('c'),
            'updated_at' => date('c')
        ],
        [
            'id' => 2,
            'slug' => 'puteshestvie-deda-moroza-2025',
            'title' => 'Новогодние мероприятия для воспитанников детских домов',
            'short' => 'Проект «Путешествие Деда Мороза с Удоканской медью» стал одним из значимых событий 2025 года.',
            'full' => "В 2025 году АНО «Культура 75» участвовала в реализации новогодних мероприятий «Путешествие Деда Мороза с Удоканской медью» для воспитанников детских домов Забайкальского края.\n\nДля грантовой заявки к такой новости полезно добавить: сколько детей участвовало, в каких районах прошли события, кто был партнером и какие отзывы получены.",
            'date' => '2025-12-25',
            'category' => 'Культура детям',
            'image' => 'assets/img/directions/grants-1.svg',
            'image2' => 'assets/img/directions/grants-2.svg',
            'image3' => 'assets/img/directions/grants-3.svg',
            'published' => true,
            'created_at' => date('c'),
            'updated_at' => date('c')
        ]
    ];
}

function app_is_vercel() {
    return (getenv('VERCEL') !== false && getenv('VERCEL') !== '') || (getenv('VERCEL_ENV') !== false && getenv('VERCEL_ENV') !== '');
}
function supabase_configured() { return SUPABASE_URL !== '' && SUPABASE_SERVICE_ROLE_KEY !== ''; }
function storage_driver() { if (supabase_configured()) return 'supabase'; if (app_is_vercel()) return 'vercel_unconfigured'; return 'local'; }
function storage_notice() {
    if (storage_driver() === 'supabase') return 'Хранилище: Supabase. Новости и фотогалереи сохраняются после деплоя на Vercel.';
    if (storage_driver() === 'vercel_unconfigured') return 'Vercel запущен без Supabase. Просмотр работает, но сохранение новостей отключено.';
    return 'Локальный режим: новости сохраняются в data/news.json, фото — в uploads.';
}

function ensure_writable_directory($dir, $label) {
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0775, true) && !is_dir($dir)) throw new RuntimeException('Не удалось создать папку ' . $label . ': ' . $dir);
    }
    if (!is_writable($dir)) throw new RuntimeException('Папка ' . $label . ' недоступна для записи: ' . $dir);
}
function ensure_data_file() {
    if (storage_driver() !== 'local') return;
    ensure_writable_directory(dirname(DATA_FILE), 'data');
    if (!file_exists(DATA_FILE)) file_put_contents(DATA_FILE, json_encode(seed_news_items(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);
    ensure_writable_directory(UPLOAD_DIR, 'uploads');
}

function http_request_raw($method, $url, $headers = [], $body = null, $timeout = 25) {
    $method = strtoupper($method);
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($body !== null && $method !== 'GET') curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($response === false) throw new RuntimeException('Ошибка HTTP-запроса: ' . $error);
        return ['status' => $status, 'body' => $response];
    }
    $opts = ['http' => ['method' => $method, 'header' => implode("\r\n", $headers), 'ignore_errors' => true, 'timeout' => $timeout]];
    if ($body !== null && $method !== 'GET') $opts['http']['content'] = $body;
    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);
    $status = 0;
    if (!empty($http_response_header[0]) && preg_match('#\s(\d{3})\s#', $http_response_header[0], $m)) $status = (int)$m[1];
    if ($response === false) throw new RuntimeException('Ошибка HTTP-запроса к внешнему хранилищу.');
    return ['status' => $status, 'body' => $response];
}

function supabase_request($method, $path, $payload = null, $extraHeaders = []) {
    if (!supabase_configured()) throw new RuntimeException('Supabase не настроен. Добавьте SUPABASE_URL и SUPABASE_SERVICE_ROLE_KEY в Vercel Environment Variables.');
    $headers = ['apikey: ' . SUPABASE_SERVICE_ROLE_KEY, 'Authorization: Bearer ' . SUPABASE_SERVICE_ROLE_KEY, 'Accept: application/json'];
    $body = null;
    if ($payload !== null) {
        $body = json_encode($payload, JSON_UNESCAPED_UNICODE);
        if ($body === false) throw new RuntimeException('Не удалось подготовить JSON для Supabase.');
        $headers[] = 'Content-Type: application/json';
    }
    foreach ($extraHeaders as $header) $headers[] = $header;
    $res = http_request_raw($method, SUPABASE_URL . $path, $headers, $body);
    if ($res['status'] >= 400 || $res['status'] === 0) throw new RuntimeException('Supabase вернул ошибку: ' . ($res['body'] ?: ('HTTP ' . $res['status'])));
    $decoded = json_decode($res['body'], true);
    return is_array($decoded) ? $decoded : [];
}

function supabase_load_news($includeDrafts = false) {
    $query = '/rest/v1/news?select=id,slug,title,short,full,date,category,image,image2,image3,published,created_at,updated_at&order=date.desc,id.desc';
    if (!$includeDrafts) $query .= '&published=eq.true';
    return array_map('normalize_news_item', supabase_request('GET', $query));
}

function normalize_news_item($item) {
    $item['id'] = (int)($item['id'] ?? 0);
    $item['published'] = filter_var($item['published'] ?? false, FILTER_VALIDATE_BOOLEAN);
    foreach (['slug','title','short','full','date','category','image','image2','image3'] as $k) $item[$k] = (string)($item[$k] ?? '');
    $item['date'] = substr($item['date'], 0, 10);
    if ($item['category'] === '') $item['category'] = 'Новости';
    if ($item['image'] === '') $item['image'] = 'assets/img/placeholder.svg';
    return $item;
}
function news_images($item) {
    $images = [];
    foreach (['image','image2','image3'] as $key) {
        $path = trim((string)($item[$key] ?? ''));
        if ($path !== '') $images[] = $path;
    }
    if (!$images) $images[] = 'assets/img/placeholder.svg';
    return array_values(array_unique($images));
}

function load_news($includeDrafts = false) {
    if (storage_driver() === 'supabase') {
        try { return supabase_load_news($includeDrafts); } catch (Throwable $e) { if (is_admin_request()) throw $e; return seed_news_items(); }
    }
    if (storage_driver() === 'vercel_unconfigured') {
        $items = seed_news_items();
        return $includeDrafts ? $items : array_values(array_filter($items, fn($item) => !empty($item['published'])));
    }
    ensure_data_file();
    $items = json_decode(file_get_contents(DATA_FILE), true);
    if (!is_array($items)) $items = [];
    $items = array_map('normalize_news_item', $items);
    if (!$includeDrafts) $items = array_values(array_filter($items, fn($item) => !empty($item['published'])));
    usort($items, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
    return $items;
}
function save_news_items($items) {
    if (storage_driver() !== 'local') throw new RuntimeException('На Vercel нельзя сохранять новости в data/news.json. Настройте Supabase.');
    ensure_data_file();
    $tmp = DATA_FILE . '.tmp';
    $json = json_encode(array_values($items), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if (file_put_contents($tmp, $json, LOCK_EX) === false || !rename($tmp, DATA_FILE)) throw new RuntimeException('Не удалось обновить файл новостей.');
}
function find_news_by_slug($slug, $includeDrafts = false) { foreach (load_news($includeDrafts) as $item) if (($item['slug'] ?? '') === $slug) return $item; return null; }
function find_news_by_id($id, $includeDrafts = true) { foreach (load_news($includeDrafts) as $item) if ((int)($item['id'] ?? 0) === (int)$id) return $item; return null; }
function next_news_id($items) { $max=0; foreach($items as $item) $max=max($max,(int)($item['id']??0)); return $max+1; }
function unique_slug($base, $items, $ignoreId = null) {
    $base = slugify($base); $slug = $base; $i = 2;
    $exists = function($candidate) use ($items, $ignoreId) { foreach ($items as $item) if (($item['slug'] ?? '') === $candidate && (int)($item['id'] ?? 0) !== (int)$ignoreId) return true; return false; };
    while ($exists($slug)) { $slug = $base . '-' . $i; $i++; }
    return $slug;
}

function upload_news_image($fieldName, $oldPath = '') {
    if (empty($_FILES[$fieldName]) || ($_FILES[$fieldName]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return $oldPath;
    $file = $_FILES[$fieldName];
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) throw new RuntimeException('Не удалось загрузить файл. Код ошибки: ' . (int)$file['error']);
    if (($file['size'] ?? 0) > MAX_UPLOAD_BYTES) throw new RuntimeException('Файл слишком большой. Максимум ' . round(MAX_UPLOAD_BYTES / 1024 / 1024, 1) . ' МБ.');
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'];
    if (!isset($allowed[$mime])) throw new RuntimeException('Можно загружать только изображения JPG, PNG, WEBP или GIF.');
    if (storage_driver() === 'supabase') return upload_news_image_supabase($file['tmp_name'], $mime, $allowed[$mime]);
    if (storage_driver() === 'vercel_unconfigured') throw new RuntimeException('Сохранение фото на Vercel требует Supabase Storage.');
    $year = date('Y'); $targetDir = UPLOAD_DIR . '/' . $year; ensure_writable_directory($targetDir, 'uploads/' . $year);
    $name = bin2hex(random_bytes(10)) . '.' . $allowed[$mime];
    $target = $targetDir . '/' . $name;
    if (!move_uploaded_file($file['tmp_name'], $target)) throw new RuntimeException('Не удалось сохранить загруженное изображение.');
    return 'uploads/' . $year . '/' . $name;
}
function upload_supabase_object($tmpName, $mime, $extension, $prefix = 'news') {
    $prefix = trim($prefix, '/');
    $path = $prefix . '/' . date('Y') . '/' . bin2hex(random_bytes(12)) . '.' . $extension;
    $url = SUPABASE_URL . '/storage/v1/object/' . rawurlencode(SUPABASE_STORAGE_BUCKET) . '/' . str_replace('%2F', '/', rawurlencode($path));
    $headers = ['apikey: ' . SUPABASE_SERVICE_ROLE_KEY, 'Authorization: Bearer ' . SUPABASE_SERVICE_ROLE_KEY, 'Content-Type: ' . $mime, 'x-upsert: true'];
    $body = file_get_contents($tmpName);
    if ($body === false) throw new RuntimeException('Не удалось прочитать временный файл изображения.');
    $res = http_request_raw('POST', $url, $headers, $body, 60);
    if ($res['status'] >= 400 || $res['status'] === 0) throw new RuntimeException('Supabase Storage не сохранил фото: ' . ($res['body'] ?: 'HTTP ' . $res['status']));
    return SUPABASE_URL . '/storage/v1/object/public/' . rawurlencode(SUPABASE_STORAGE_BUCKET) . '/' . str_replace('%2F', '/', rawurlencode($path));
}
function upload_news_image_supabase($tmpName, $mime, $extension) {
    return upload_supabase_object($tmpName, $mime, $extension, 'news');
}

function upsert_news_from_post($id = null) {
    if (storage_driver() === 'supabase') return upsert_news_from_post_supabase($id);
    if (storage_driver() === 'vercel_unconfigured') throw new RuntimeException('На Vercel сохранение включается через Supabase.');
    $items = load_news(true); $now = date('c');
    $title=trim($_POST['title']??''); $short=trim($_POST['short']??''); $full=trim($_POST['full']??''); $date=trim($_POST['date']??date('Y-m-d')); $category=trim($_POST['category']??'Новости'); $published=!empty($_POST['published']);
    if ($title==='' || $short==='' || $full==='' || $date==='') throw new RuntimeException('Заполните заголовок, краткое описание, полное описание и дату.');
    if (substr($date, 0, 4) < '2025') throw new RuntimeException('Новости можно добавлять только с 2025 года.');
    if ($id === null) { $id = next_news_id($items); $item = ['id'=>$id, 'created_at'=>$now]; } else { $item = find_news_by_id($id, true); if (!$item) throw new RuntimeException('Новость не найдена.'); }
    $item['title']=$title; $item['short']=$short; $item['full']=$full; $item['date']=$date; $item['category']=$category?:'Новости'; $item['published']=$published; $item['updated_at']=$now; $item['slug']=unique_slug($title,$items,$id);
    $item['image']=upload_news_image('image', $item['image'] ?? '');
    $item['image2']=upload_news_image('image2', $item['image2'] ?? '');
    $item['image3']=upload_news_image('image3', $item['image3'] ?? '');
    if (($item['image'] ?? '') === '') $item['image']='assets/img/placeholder.svg';
    $updated=false; foreach($items as $idx=>$existing) if ((int)($existing['id']??0)===(int)$id) { $items[$idx]=$item; $updated=true; break; }
    if (!$updated) $items[]=$item; save_news_items($items); return $item;
}
function upsert_news_from_post_supabase($id = null) {
    $items=load_news(true); $now=gmdate('c');
    $title=trim($_POST['title']??''); $short=trim($_POST['short']??''); $full=trim($_POST['full']??''); $date=trim($_POST['date']??date('Y-m-d')); $category=trim($_POST['category']??'Новости'); $published=!empty($_POST['published']);
    if ($title==='' || $short==='' || $full==='' || $date==='') throw new RuntimeException('Заполните заголовок, краткое описание, полное описание и дату.');
    if (substr($date, 0, 4) < '2025') throw new RuntimeException('Новости можно добавлять только с 2025 года.');
    $existing=null; if ($id !== null) { $existing=find_news_by_id($id,true); if(!$existing) throw new RuntimeException('Новость не найдена.'); }
    $payload=[
        'slug'=>unique_slug($title,$items,$id), 'title'=>$title, 'short'=>$short, 'full'=>$full, 'date'=>$date, 'category'=>$category?:'Новости',
        'image'=>upload_news_image('image', $existing['image'] ?? ''),
        'image2'=>upload_news_image('image2', $existing['image2'] ?? ''),
        'image3'=>upload_news_image('image3', $existing['image3'] ?? ''),
        'published'=>$published, 'updated_at'=>$now
    ];
    if ($payload['image'] === '') $payload['image']='assets/img/placeholder.svg';
    if ($id === null) { $payload['created_at']=$now; $result=supabase_request('POST','/rest/v1/news',$payload,['Prefer: return=representation']); return normalize_news_item($result[0] ?? $payload); }
    $result=supabase_request('PATCH','/rest/v1/news?id=eq.'.rawurlencode((string)$id),$payload,['Prefer: return=representation']); return normalize_news_item($result[0] ?? array_merge($existing,$payload));
}
function delete_news_by_id($id) {
    if (storage_driver()==='supabase') { supabase_request('DELETE','/rest/v1/news?id=eq.'.rawurlencode((string)$id)); return; }
    if (storage_driver()==='vercel_unconfigured') throw new RuntimeException('На Vercel удаление новостей требует Supabase.');
    $items=load_news(true); save_news_items(array_values(array_filter($items, fn($item)=>(int)($item['id']??0)!==(int)$id)));
}

function seed_directions_items() {
    return [
        [
            'slug' => 'culture', 'num' => '01', 'title' => 'Культурные события',
            'lead' => 'Организация и проведение фестивалей, конкурсов, выставок, концертов, творческих встреч и культурно-зрелищных мероприятий.',
            'points' => ['Разработка программы события и визуальной концепции.','Подготовка площадки, участников и информационного сопровождения.','Фотоотчет, новости и фиксация результатов для публичной отчетности.'],
            'image1' => 'assets/img/directions/culture-1.svg', 'image2' => 'assets/img/directions/culture-2.svg', 'image3' => 'assets/img/directions/culture-3.svg',
            'caption1' => 'Материал 1', 'caption2' => 'Материал 2', 'caption3' => 'Материал 3', 'sort_order' => 1
        ],
        [
            'slug' => 'cinema', 'num' => '02', 'title' => 'Кино и медиапроекты',
            'lead' => 'Кинопоказы, кинопрограммы, документальные и просветительские форматы, создание видеоматериалов о культурной жизни Забайкалья.',
            'points' => ['Показы и обсуждения фильмов для разных аудиторий.','Медиасопровождение культурных инициатив.','Развитие интереса к региональной киноиндустрии.'],
            'image1' => 'assets/img/directions/cinema-1.svg', 'image2' => 'assets/img/directions/cinema-2.svg', 'image3' => 'assets/img/directions/cinema-3.svg',
            'caption1' => 'Материал 1', 'caption2' => 'Материал 2', 'caption3' => 'Материал 3', 'sort_order' => 2
        ],
        [
            'slug' => 'education', 'num' => '03', 'title' => 'Образование и проектная подготовка',
            'lead' => 'Команда проходит обучение, семинары и консультации, чтобы качественно готовить заявки, участвовать в грантовых проектах и представлять их партнерам.',
            'points' => ['Осваиваем проектную логику: цель, задачи, целевые группы, календарный план и бюджет.','Готовы участвовать в грантовых конкурсах и представлять социально-культурные проекты.','Проводим лекции, мастер-классы и творческие лаборатории в сфере кино, культуры и искусства.'],
            'image1' => 'assets/img/directions/education-1.svg', 'image2' => 'assets/img/directions/education-2.svg', 'image3' => 'assets/img/directions/education-3.svg',
            'caption1' => 'Материал 1', 'caption2' => 'Материал 2', 'caption3' => 'Материал 3', 'sort_order' => 3
        ],
        [
            'slug' => 'leisure', 'num' => '04', 'title' => 'Культурный досуг',
            'lead' => 'Современные формы досуга для жителей разных возрастов: встречи, клубные форматы, кинопоказы, просветительские программы.',
            'points' => ['Делаем культурные события доступнее для жителей региона.','Учитываем потребности разных социально-возрастных групп.','Создаем пространство общения, творчества и развития.'],
            'image1' => 'assets/img/directions/leisure-1.svg', 'image2' => 'assets/img/directions/leisure-2.svg', 'image3' => 'assets/img/directions/leisure-3.svg',
            'caption1' => 'Материал 1', 'caption2' => 'Материал 2', 'caption3' => 'Материал 3', 'sort_order' => 4
        ],
        [
            'slug' => 'partners', 'num' => '05', 'title' => 'Партнерство',
            'lead' => 'Сотрудничество с органами власти, учреждениями культуры, бизнесом, авторами, исполнителями, волонтерами и общественными инициативами.',
            'points' => ['Министерство культуры Забайкальского края.','ООО «Удоканская медь».','Учреждения культуры, творческие коллективы, авторы, исполнители, волонтерские и добровольческие движения.'],
            'image1' => 'assets/img/directions/partners-1.svg', 'image2' => 'assets/img/directions/partners-2.svg', 'image3' => 'assets/img/directions/partners-3.svg',
            'caption1' => 'Материал 1', 'caption2' => 'Материал 2', 'caption3' => 'Материал 3', 'sort_order' => 5
        ],
        [
            'slug' => 'grants', 'num' => '06', 'title' => 'Грантовые проекты',
            'lead' => 'Подготовка и реализация социальных, социокультурных, культурных, профессиональных и любительских программ через субсидии и грантовые конкурсы.',
            'points' => ['Описание актуальности, целевых групп и измеримых результатов.','Сбор публичных подтверждений: новости, фотоотчеты, партнеры, отзывы.','Планирование календаря, бюджета и информационного сопровождения.'],
            'image1' => 'assets/img/directions/grants-1.svg', 'image2' => 'assets/img/directions/grants-2.svg', 'image3' => 'assets/img/directions/grants-3.svg',
            'caption1' => 'Материал 1', 'caption2' => 'Материал 2', 'caption3' => 'Материал 3', 'sort_order' => 6
        ],
    ];
}

function directions_data_file() { return __DIR__ . '/../data/directions.json'; }

function points_text_to_array($points) {
    if (is_array($points)) return array_values(array_filter(array_map('trim', $points), fn($v) => $v !== ''));
    $lines = preg_split('/\r\n|\r|\n/u', (string)$points);
    $lines = array_map(function($line) { return preg_replace('/^\s*[-•]\s*/u', '', trim($line)); }, $lines);
    return array_values(array_filter($lines, fn($v) => $v !== ''));
}

function points_array_to_text($points) {
    return implode("\n", points_text_to_array($points));
}

function normalize_direction_item($item) {
    $defaults = ['slug'=>'','num'=>'','title'=>'','lead'=>'','points'=>[], 'image1'=>'','image2'=>'','image3'=>'', 'caption1'=>'Материал 1', 'caption2'=>'Материал 2', 'caption3'=>'Материал 3', 'sort_order'=>0];
    $item = array_merge($defaults, is_array($item) ? $item : []);
    foreach (['slug','num','title','lead','image1','image2','image3','caption1','caption2','caption3'] as $k) $item[$k] = trim((string)($item[$k] ?? ''));
    $item['points'] = points_text_to_array($item['points'] ?? []);
    $item['sort_order'] = (int)($item['sort_order'] ?? 0);
    return $item;
}

function ensure_directions_file() {
    if (storage_driver() !== 'local') return;
    ensure_writable_directory(dirname(directions_data_file()), 'data');
    if (!file_exists(directions_data_file())) {
        file_put_contents(directions_data_file(), json_encode(seed_directions_items(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);
    }
}

function supabase_load_directions() {
    $query = '/rest/v1/directions?select=slug,num,title,lead,points,image1,image2,image3,caption1,caption2,caption3,sort_order,updated_at&order=sort_order.asc,slug.asc';
    $items = supabase_request('GET', $query);
    if (!$items) return seed_directions_items();
    return array_map('normalize_direction_item', $items);
}

function load_directions($includeDrafts = false) {
    if (storage_driver() === 'supabase') {
        try { return supabase_load_directions(); } catch (Throwable $e) { if (is_admin_request()) throw $e; return seed_directions_items(); }
    }
    ensure_directions_file();
    $items = json_decode(@file_get_contents(directions_data_file()), true);
    if (!is_array($items)) $items = seed_directions_items();
    $items = array_map('normalize_direction_item', $items);
    usort($items, fn($a, $b) => ((int)($a['sort_order'] ?? 0)) <=> ((int)($b['sort_order'] ?? 0)) ?: strcmp($a['slug'] ?? '', $b['slug'] ?? ''));
    return $items;
}

function save_directions_items($items) {
    if (storage_driver() !== 'local') throw new RuntimeException('На Vercel направления сохраняются через Supabase.');
    ensure_directions_file();
    $json = json_encode(array_values($items), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if (file_put_contents(directions_data_file(), $json, LOCK_EX) === false) throw new RuntimeException('Не удалось сохранить направления.');
}

function find_direction_by_slug($slug, $includeDrafts = false) {
    foreach (load_directions($includeDrafts) as $item) if (($item['slug'] ?? '') === $slug) return $item;
    return null;
}

function direction_images($item) {
    $images = [];
    for ($i = 1; $i <= 3; $i++) {
        $src = trim((string)($item['image' . $i] ?? ''));
        if ($src === '') continue;
        $images[] = ['src' => $src, 'caption' => trim((string)($item['caption' . $i] ?? ('Материал ' . $i)))];
    }
    if (!$images) $images[] = ['src' => 'assets/img/placeholder.svg', 'caption' => 'Материал'];
    return $images;
}

function upload_direction_image($fieldName, $oldPath = '', $slug = 'direction') {
    if (empty($_FILES[$fieldName]) || ($_FILES[$fieldName]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return $oldPath;
    $file = $_FILES[$fieldName];
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) throw new RuntimeException('Не удалось загрузить файл. Код ошибки: ' . (int)$file['error']);
    if (($file['size'] ?? 0) > MAX_UPLOAD_BYTES) throw new RuntimeException('Файл слишком большой. Максимум ' . round(MAX_UPLOAD_BYTES / 1024 / 1024, 1) . ' МБ.');
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'];
    if (!isset($allowed[$mime])) throw new RuntimeException('Можно загружать только изображения JPG, PNG, WEBP или GIF.');
    if (storage_driver() === 'supabase') return upload_supabase_object($file['tmp_name'], $mime, $allowed[$mime], 'directions/' . slugify($slug));
    if (storage_driver() === 'vercel_unconfigured') throw new RuntimeException('Сохранение фото на Vercel требует Supabase Storage.');
    $targetDir = UPLOAD_DIR . '/directions/' . slugify($slug);
    ensure_writable_directory($targetDir, 'uploads/directions/' . slugify($slug));
    $name = bin2hex(random_bytes(10)) . '.' . $allowed[$mime];
    $target = $targetDir . '/' . $name;
    if (!move_uploaded_file($file['tmp_name'], $target)) throw new RuntimeException('Не удалось сохранить изображение направления.');
    return 'uploads/directions/' . slugify($slug) . '/' . $name;
}

function upsert_direction_from_post($slug) {
    $existing = find_direction_by_slug($slug, true);
    if (!$existing) throw new RuntimeException('Направление не найдено.');
    $title = trim($_POST['title'] ?? '');
    $lead = trim($_POST['lead'] ?? '');
    $num = trim($_POST['num'] ?? ($existing['num'] ?? ''));
    $points = points_text_to_array($_POST['points'] ?? '');
    if ($title === '' || $lead === '') throw new RuntimeException('Заполните название и краткую справку.');
    $item = $existing;
    $item['num'] = $num;
    $item['title'] = $title;
    $item['lead'] = $lead;
    $item['points'] = $points;
    for ($i = 1; $i <= 3; $i++) {
        $item['caption' . $i] = trim($_POST['caption' . $i] ?? ($existing['caption' . $i] ?? ('Материал ' . $i)));
        $item['image' . $i] = upload_direction_image('image' . $i, $existing['image' . $i] ?? '', $slug);
    }
    if (storage_driver() === 'supabase') {
        $payload = [
            'num' => $item['num'],
            'title' => $item['title'],
            'lead' => $item['lead'],
            'points' => points_array_to_text($item['points']),
            'image1' => $item['image1'], 'image2' => $item['image2'], 'image3' => $item['image3'],
            'caption1' => $item['caption1'], 'caption2' => $item['caption2'], 'caption3' => $item['caption3'],
            'updated_at' => gmdate('c')
        ];
        $result = supabase_request('PATCH', '/rest/v1/directions?slug=eq.' . rawurlencode($slug), $payload, ['Prefer: return=representation']);
        return normalize_direction_item($result[0] ?? array_merge($item, $payload));
    }
    $items = load_directions(true);
    foreach ($items as $idx => $dir) {
        if (($dir['slug'] ?? '') === $slug) { $items[$idx] = $item; save_directions_items($items); return $item; }
    }
    throw new RuntimeException('Не удалось обновить направление.');
}

function available_years() { $years=[]; foreach(load_news(false) as $item){ $year=substr($item['date']??'',0,4); if($year && (int)$year>=2025) $years[$year]=$year; } rsort($years); return array_values($years); }
?>
