<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Роман Старшинов — Стихи</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Caveat:wght@700&display=swap" rel="stylesheet">

    <style>
        :root { --text-main: #f4f1ea; --text-muted: #b0b5aa; --accent: #7d9472; --card-bg: rgba(30, 35, 30, 0.75); }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Montserrat', sans-serif; color: var(--text-main); line-height: 1.6;
            background-color: #121411; background-image: linear-gradient(rgba(15, 18, 15, 0.85), rgba(15, 18, 15, 0.95)), url('bg.jpg');
            background-size: cover; background-position: center; background-attachment: fixed;
        }
        section { scroll-margin-top: 100px; }
        header { display: flex; justify-content: space-between; align-items: center; padding: 20px 5%; background-color: rgba(15, 18, 15, 0.8); backdrop-filter: blur(10px); position: sticky; top: 0; z-index: 100; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .logo { font-family: 'Caveat', cursive; font-size: 36px; font-weight: 700; letter-spacing: 1px; text-decoration: none; color: var(--text-main); }
        nav a { margin-left: 25px; text-decoration: none; color: var(--text-main); font-weight: 600; font-size: 16px; transition: color 0.3s; }
        nav a:hover { color: var(--accent); }
        
        .hero { display: flex; align-items: center; justify-content: space-between; max-width: 1200px; margin: 60px auto; padding: 0 20px; gap: 50px; min-height: 70vh; }
        .hero-text { flex: 1; }
        .hero-text h1 { font-family: 'Caveat', cursive; font-size: 80px; line-height: 1.1; margin-bottom: 20px; color: #ffffff; }
        .hero-text p { font-size: 20px; color: var(--text-muted); max-width: 500px; margin-bottom: 30px; }
        .hero-photo-container { flex: 1; display: flex; justify-content: flex-end; }
        .author-photo { width: 100%; max-width: 400px; height: 500px; object-fit: cover; border-radius: 15px; box-shadow: 15px 15px 0px rgba(125, 148, 114, 0.4); transition: transform 0.3s ease; filter: grayscale(80%) contrast(110%); }
        .author-photo:hover { transform: translate(-5px, -5px); box-shadow: 20px 20px 0px rgba(125, 148, 114, 0.6); }
        
        .section-container { max-width: 1000px; margin: 100px auto; padding: 0 20px; }
        .section-title { font-family: 'Caveat', cursive; font-size: 56px; text-align: center; margin-bottom: 40px; color: #ffffff; }
        .about-text { font-size: 18px; line-height: 1.8; text-align: center; max-width: 800px; margin: 0 auto; background: var(--card-bg); padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.05); }
        
        .poems-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        .poem-card { background-color: var(--card-bg); padding: 30px; border-radius: 10px; border-left: 4px solid var(--accent); box-shadow: 0 10px 30px rgba(0,0,0,0.3); transition: transform 0.3s, background-color 0.3s; backdrop-filter: blur(10px); border-top: 1px solid rgba(255,255,255,0.05); border-right: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); }
        .poem-card:hover { transform: translateY(-5px); background-color: rgba(40, 45, 40, 0.85); }
        .poem-card h3 { font-family: 'Caveat', cursive; font-size: 36px; margin-bottom: 15px; color: #ffffff; }
        .poem-card p { font-size: 15px; color: var(--text-muted); font-style: italic; white-space: pre-line; }
        
        .quote-section { text-align: center; padding: 80px 20px; background-color: rgba(0,0,0,0.3); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); margin: 60px 0; }
        .quote-section blockquote { font-family: 'Caveat', cursive; font-size: 48px; color: var(--accent); max-width: 800px; margin: 0 auto; line-height: 1.2; }
        
        /* СТИЛИ ДЛЯ НОВОСТЕЙ */
        .news-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; max-width: 1000px; margin: 0 auto; }
        .news-card { background: var(--card-bg); border-radius: 15px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05); backdrop-filter: blur(10px); box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .news-card img { width: 100%; height: 200px; object-fit: cover; }
        .news-content { padding: 20px; }
        .news-date { color: var(--accent); font-size: 12px; font-weight: bold; display: block; margin-bottom: 5px; }
        .news-content h3 { font-family: 'Caveat', cursive; font-size: 28px; color: #fff; margin-bottom: 10px; }
        .news-content p { font-size: 15px; color: var(--text-muted); }
        
        .events-list { display: flex; flex-direction: column; gap: 20px; max-width: 800px; margin: 0 auto; }
        .event-card { display: flex; align-items: center; background: var(--card-bg); padding: 20px 30px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.05); backdrop-filter: blur(10px); transition: transform 0.3s, border-color 0.3s; }
        .event-card:hover { transform: translateX(10px); border-color: var(--accent); }
        .event-date { display: flex; flex-direction: column; align-items: center; justify-content: center; background: rgba(255,255,255,0.1); min-width: 70px; padding: 10px; border-radius: 10px; margin-right: 25px; }
        .event-date .day { font-size: 28px; font-weight: 700; color: var(--accent); }
        .event-date .month { font-size: 14px; text-transform: uppercase; color: var(--text-muted); }
        .event-info { flex: 1; }
        .event-info h3 { font-size: 18px; margin-bottom: 5px; color: #fff; }
        .event-info p { font-size: 14px; color: var(--text-muted); }
        .btn-small { display: inline-block; padding: 10px 20px; background: transparent; color: var(--text-main); border: 1px solid rgba(255,255,255,0.3); border-radius: 50px; text-decoration: none; font-size: 14px; transition: all 0.3s; }
        .btn-small:hover { background: var(--accent); border-color: var(--accent); }
        
        .subscribe-section { max-width: 1000px; margin: 100px auto; padding: 0 20px; }
        .subscribe-box { background: linear-gradient(135deg, rgba(45, 50, 45, 0.9), rgba(30, 35, 30, 0.9)); padding: 50px; border-radius: 20px; text-align: center; border: 1px solid var(--accent); box-shadow: 0 15px 40px rgba(0,0,0,0.5); backdrop-filter: blur(10px); }
        .subscribe-box h2 { font-family: 'Caveat', cursive; font-size: 42px; color: #fff; margin-bottom: 10px; }
        .subscribe-box p { color: var(--text-muted); margin-bottom: 30px; font-size: 16px; }
        .subscribe-form { display: flex; flex-direction: column; align-items: center; gap: 20px; max-width: 400px; margin: 0 auto; }
        .contact-tabs { display: flex; width: 100%; background: rgba(0,0,0,0.3); border-radius: 50px; padding: 5px; }
        .tab-label { flex: 1; padding: 10px; text-align: center; border-radius: 50px; cursor: pointer; font-size: 14px; font-weight: 600; color: var(--text-muted); transition: all 0.3s; }
        .contact-tabs input[type="radio"] { display: none; }
        .contact-tabs input[type="radio"]:checked + .tab-label { background: var(--accent); color: #fff; }
        .input-field { width: 100%; padding: 15px 20px; border-radius: 50px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.3); color: #fff; font-size: 16px; font-family: 'Montserrat', sans-serif; outline: none; transition: border-color 0.3s; }
        .input-field:focus { border-color: var(--accent); }
        .input-field.hidden { display: none; }
        .checkbox-group { width: 100%; text-align: left; font-size: 13px; color: var(--text-muted); }
        .checkbox-group label { display: flex; align-items: flex-start; gap: 10px; cursor: pointer; }
        .checkbox-group input[type="checkbox"] { accent-color: var(--accent); width: 18px; height: 18px; cursor: pointer; flex-shrink: 0; margin-top: 2px; }
        .checkbox-group a { color: var(--accent); text-decoration: underline; }
        
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8); backdrop-filter: blur(5px); }
        .modal-content { background-color: #1a1f18; color: var(--text-main); margin: 10% auto; padding: 30px; border: 1px solid var(--accent); border-radius: 15px; width: 80%; max-width: 600px; position: relative; max-height: 80vh; overflow-y: auto; }
        .close-btn { color: var(--text-muted); float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
        .close-btn:hover { color: var(--accent); }
        .modal h3 { margin-top: 20px; margin-bottom: 10px; color: var(--accent); font-size: 18px; }
        .modal ul { margin-left: 20px; margin-bottom: 15px; color: var(--text-muted); }
        
        .contacts-box { text-align: center; background: var(--card-bg); padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.05); }
        .contacts-box p { font-size: 18px; margin-bottom: 25px; color: var(--text-muted); }
        .social-links a { display: inline-block; margin: 0 15px; font-size: 18px; color: var(--text-main); text-decoration: none; font-weight: 600; border-bottom: 2px solid var(--accent); padding-bottom: 5px; transition: color 0.3s; }
        .social-links a:hover { color: var(--accent); }
        
        .btn { display: inline-block; background-color: var(--accent); color: #ffffff; padding: 15px 40px; font-size: 18px; text-decoration: none; border-radius: 50px; font-weight: 600; letter-spacing: 1px; transition: all 0.3s; border: none; cursor: pointer; width: 100%; }
        .btn:hover { background-color: #93ad86; transform: scale(1.02); }
        
        .cookie-banner { position: fixed; bottom: 20px; left: 20px; right: 20px; max-width: 600px; margin: 0 auto; background-color: #1a1f18; border: 1px solid var(--accent); padding: 20px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); display: flex; flex-direction: column; gap: 15px; z-index: 999; opacity: 0; transform: translateY(100%); transition: all 0.5s ease; }
        .cookie-banner.show { opacity: 1; transform: translateY(0); }
        .cookie-banner p { font-size: 14px; color: var(--text-main); margin: 0; }
        .cookie-banner .btn { width: auto; padding: 10px 30px; font-size: 14px; align-self: flex-end; }
        
        footer { text-align: center; padding: 30px; background-color: rgba(10, 12, 10, 0.9); color: var(--text-muted); font-size: 14px; border-top: 1px solid rgba(255,255,255,0.05); }
        .footer-info { margin-top: 10px; font-size: 12px; opacity: 0.7; }
        
        @media (max-width: 900px) {
            .hero { flex-direction: column; text-align: center; margin: 40px auto; }
            .hero-text p { margin: 0 auto 30px auto; }
            .hero-photo-container { justify-content: center; width: 100%; }
            .author-photo { height: 400px; }
            .hero-text h1 { font-size: 60px; }
            .quote-section blockquote { font-size: 32px; }
            nav { display: flex; flex-direction: column; text-align: right; gap: 10px; }
            nav a { margin-left: 0; }
            .event-card { flex-direction: column; text-align: center; gap: 15px; }
            .event-date { margin-right: 0; }
            .subscribe-box { padding: 30px 20px; }
            .cookie-banner { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

    <header>
        <a href="#" class="logo">Р. Старшинов</a>
        <nav>
            <a href="#about">Обо мне</a>
            <a href="#poems">Стихи</a>
            <a href="#news">Новости</a> <!-- ДОБАВЛЕНА ССЫЛКА -->
            <a href="#events">Афиша</a>
            <a href="#contacts">Контакты</a>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-text">
            <h1>Роман<br>Старшинов</h1>
            <p>Современная поэзия о жизни, чувствах, городе и нас с вами. Без цензуры и лишних слов.</p>
            <a href="#poems" class="btn">Читать все стихи</a>
        </div>
        <div class="hero-photo-container">
            <img src="photo.jpg" alt="Роман Старшинов" class="author-photo"
                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=800&auto=format&fit=crop';">
        </div>
    </section>

    <section id="about" class="section-container">
        <h2 class="section-title">Обо мне</h2>
        <div class="about-text">
            <p>Привет. Меня зовут Роман. Я пишу стихи о том, что вижу вокруг: о серых улицах, случайных прохожих, любви и разочарованиях. Номинант премии «Неформат». В моих текстах нет пафоса, только ритм города и честные эмоции.</p>
        </div>
    </section>

    <div class="quote-section">
        <blockquote>«Мы с тобой — как сломанный телефон,<br>Молчим о том, чего не будет в этом лете...»</blockquote>
    </div>

    <section id="poems" class="section-container">
        <h2 class="section-title">Избранное</h2>
        <div class="poems-grid">
            <article class="poem-card">
                <h3>Как отдых</h3>
                <p>Я выключаю телефон,
                И город затихает в тусклом свете.
                Мы с тобой — как сломанный телефон,
                Молчим о том, чего не будет в этом лете.</p>
            </article>
            <article class="poem-card">
                <h3>Кто-то подумал</h3>
                <p>Кто-то подумал, что мы уйдем,
                Растворимся в бетонных стенах.
                Но мы останемся под дождем,
                В недостроенных наших сменах.</p>
            </article>
            <article class="poem-card">
                <h3>100000 рублей</h3>
                <p>За сто тысяч рублей можно купить покой,
                Или билет в один конец.
                Но я стою перед тобой,
                Как перед иконой — нагой и наглец.</p>
            </article>
            <article class="poem-card">
                <h3>Я не устал</h3>
                <p>Я не устал, просто выдохся в ноль.
                Еще один день, еще одна роль.
                Завтра я снова начну этот бой,
                Пока в моих венах течет этот город.</p>
            </article>
        </div>
    </section>

    <section id="more-poems" class="section-container">
        <h2 class="section-title">Ещё стихи</h2>
        <div class="poems-grid">
            <article class="poem-card">
                <h3>Пока я жил на этом свете...</h3>
                <p>Пока я жил на этом свете,
                За двадцать лет
                Я только то узнал о смерти,
                Что смерти нет.

                Когда она, меня заметив,
                За мной брела,
                Я знал, что смерти нет на свете,
                А жизнь была.</p>
            </article>
            <article class="poem-card">
                <h3>Прирожденный горожанин</h3>
                <p>Прирожденный горожанин,
                Я тоскую по лугам,
                По полям, по рощам ранним,
                По нетронутым снегам.

                Прирожденные крестьяне
                Устремились в города,
                Забывают испытанье
                Первозданного труда.

                Но во сне на мягком ложе
                Видят рощи и туман.
                И тоскуют. Значит, тоже
                Превратились в горожан.</p>
            </article>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- НОВЫЙ БЛОК: НОВОСТИ (Вывод из БД) -->
    <!-- ========================================== -->
    <section id="news" class="section-container">
        <h2 class="section-title">Новости</h2>
        
        <div class="news-grid">
            <?php
            // Подключаемся к БД для вывода новостей
            $pdo_news = new PDO("mysql:host=localhost;dbname=poet_site_db;charset=utf8mb4", "root", "");
            $stmt_news = $pdo_news->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 2"); 
            $news_items = $stmt_news->fetchAll();
            
            if (count($news_items) > 0) {
                foreach ($news_items as $item) {
                    echo '<article class="news-card">';
                    if ($item['image']) {
                        echo '<img src="uploads/' . htmlspecialchars($item['image']) . '" alt="Новость">';
                    }
                    echo '<div class="news-content">';
                    echo '<span class="news-date">' . date('d.m.Y', strtotime($item['created_at'])) . '</span>';
                    echo '<h3>' . htmlspecialchars($item['title']) . '</h3>';
                    echo '<p>' . nl2br(htmlspecialchars($item['content'])) . '</p>';
                    echo '</div></article>';
                }
            } else {
                echo '<p style="text-align:center; width:100%; color: #b0b5aa;">Пока новостей нет. Скоро здесь появятся анонсы!</p>';
            }
            ?>
        </div>
    </section>

    <section id="events" class="section-container">
        <h2 class="section-title">Ближайшие выступления</h2>
        <div class="events-list">
            <div class="event-card">
                <div class="event-date">
                    <span class="day">12</span>
                    <span class="month">Окт</span>
                </div>
                <div class="event-info">
                    <h3>Поэтический вечер «Городские шумы»</h3>
                    <p>Москва, Книжный клуб «Достоевский»</p>
                </div>
                <a href="#" class="btn-small">Подробнее</a>
            </div>
            <div class="event-card">
                <div class="event-date">
                    <span class="day">25</span>
                    <span class="month">Окт</span>
                </div>
                <div class="event-info">
                    <h3>Квартирник: Стихи под гитару</h3>
                    <p>Санкт-Петербург, арт-пространство «Люмьер»</p>
                </div>
                <a href="#" class="btn-small">Подробнее</a>
            </div>
        </div>
    </section>

    <section class="subscribe-section">
        <div class="subscribe-box">
            <h2>Новые стихи — раз в неделю</h2>
            <p>Выберите удобный мессенджер или почту для подписки.</p>
            
            <form class="subscribe-form" action="send.php" method="POST">
                
                <div class="contact-tabs">
                    <input type="radio" id="tab-email" name="contact_method" value="email" checked>
                    <label class="tab-label" for="tab-email">E-mail</label>

                    <input type="radio" id="tab-telegram" name="contact_method" value="telegram">
                    <label class="tab-label" for="tab-telegram">Telegram</label>

                    <input type="radio" id="tab-max" name="contact_method" value="max">
                    <label class="tab-label" for="tab-max">Max</label>
                </div>

                <input type="email" name="email" id="input-email" class="input-field" placeholder="Ваш e-mail" required>
                <input type="text" name="telegram" id="input-telegram" class="input-field hidden" placeholder="Ваш ник в Telegram (@username)">
                <input type="text" name="max" id="input-max" class="input-field hidden" placeholder="Ваш ник в Max (@username)">
                
                <div class="checkbox-group">
                    <label>
                        <input type="checkbox" name="privacy_agreed" required>
                        <span>Я принимаю <a href="#" id="open-policy">Политику конфиденциальности</a> и даю <a href="#" id="open-consent">Согласие на обработку персональных данных</a></span>
                    </label>
                </div>

                <button type="submit" class="btn">Подписаться</button>
            </form>
        </div>
    </section>

    <section id="contacts" class="section-container">
        <h2 class="section-title">Контакты</h2>
        <div class="contacts-box">
            <p>Связаться со мной или предложить сотрудничество:</p>
            <div class="social-links">
                <a href="#">Telegram</a>
                <a href="#">ВКонтакте</a>
                <a href="mailto:roman@example.com">E-mail</a>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2026 Роман Старшинов. Все стихи принадлежат автору.</p>
        <p class="footer-info">Самозанятый Старшинов Р.А. | ИНН: 123456789012 | Связь: roman@example.com</p>
    </footer>

    <div id="consent-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="close-consent">&times;</span>
            <h2>Согласие на обработку персональных данных</h2>
            <p style="margin-top: 15px;">Настоящим я, действуя свободно, своей волей и в своем интересе, даю согласие Администрации сайта на обработку моих персональных данных на следующих условиях:</p>
            <h3>1. Перечень персональных данных</h3>
            <p>Сайт обрабатывает следующие данные, которые я добровольно указываю в форме подписки:</p>
            <ul>
                <li>Адрес электронной почты (e-mail);</li>
                <li>Имя пользователя в мессенджере Telegram;</li>
                <li>Имя пользователя в мессенджере Max.</li>
            </ul>
            <h3>2. Цели обработки</h3>
            <p>Мои персональные данные обрабатываются исключительно в целях:</p>
            <ul>
                <li>Отправки анонсов новых стихотворений и литературных новостей;</li>
                <li>Информирования о предстоящих выступлениях и мероприятиях;</li>
                <li>Обратной связи (при необходимости).</li>
            </ul>
            <h3>3. Условия обработки</h3>
            <p>Обработка персональных данных включает в себя сбор, запись, систематизацию, хранение и использование данных. Сайт обязуется не передавать мои данные третьим лицам и не использовать их для рассылки спама.</p>
            <h3>4. Отзыв согласия</h3>
            <p>Я уведомлен(а), что могу в любой момент отозвать свое согласие на обработку персональных данных, направив письмо на адрес электронной почты, указанный в разделе «Контакты».</p>
        </div>
    </div>

    <div id="policy-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="close-policy">&times;</span>
            <h2>Политика конфиденциальности</h2>
            <h3>1. Общие положения</h3>
            <p>Настоящая Политика конфиденциальности описывает, как сайт Романа Старшинова собирает, использует и защищает информацию, которую вы предоставляете при использовании сайта.</p>
            <h3>2. Какую информацию мы собираем</h3>
            <p>Мы собираем только те данные, которые вы добровольно вводите в форму подписки (E-mail, никнейм в Telegram или Max). Также сайт автоматически собирает технические данные (файлы cookie) для анализа посещаемости.</p>
            <h3>3. Как мы используем вашу информацию</h3>
            <p>Ваши контактные данные используются исключительно для отправки вам новых стихотворений, анонсов выступлений и новостей проекта. Мы гарантируем, что ваши данные не будут переданы третьим лицам, проданы или использованы для спама.</p>
            <h3>4. Использование файлов Cookie</h3>
            <p>Наш сайт использует файлы cookie для обеспечения корректной работы страницы и сбора анонимной статистики. Вы можете отключить cookie в настройках вашего браузера, но это может повлиять на работу некоторых элементов сайта.</p>
            <h3>5. Защита данных</h3>
            <p>Мы принимаем необходимые организационные и технические меры для защиты ваших персональных данных от неправомерного доступа, уничтожения или изменения.</p>
        </div>
    </div>

    <div id="cookie-banner" class="cookie-banner">
        <p>Мы используем файлы cookie для корректной работы сайта и анализа трафика. Продолжая пользоваться сайтом, вы соглашаетесь с этим.</p>
        <button id="accept-cookies" class="btn">Принять</button>
    </div>

    <script>
        const consentModal = document.getElementById("consent-modal");
        const btnConsent = document.getElementById("open-consent");
        const closeConsent = document.getElementById("close-consent");
        btnConsent.onclick = function(e) { e.preventDefault(); consentModal.style.display = "block"; }
        closeConsent.onclick = function() { consentModal.style.display = "none"; }

        const policyModal = document.getElementById("policy-modal");
        const btnPolicy = document.getElementById("open-policy");
        const closePolicy = document.getElementById("close-policy");
        btnPolicy.onclick = function(e) { e.preventDefault(); policyModal.style.display = "block"; }
        closePolicy.onclick = function() { policyModal.style.display = "none"; }

        window.onclick = function(event) {
            if (event.target == consentModal) { consentModal.style.display = "none"; }
            if (event.target == policyModal) { policyModal.style.display = "none"; }
        }

        const tabEmail = document.getElementById('tab-email');
        const tabTelegram = document.getElementById('tab-telegram');
        const tabMax = document.getElementById('tab-max');
        const inputEmail = document.getElementById('input-email');
        const inputTelegram = document.getElementById('input-telegram');
        const inputMax = document.getElementById('input-max');

        function updateForm() {
            inputEmail.classList.add('hidden');
            inputTelegram.classList.add('hidden');
            inputMax.classList.add('hidden');
            inputEmail.removeAttribute('required');
            inputTelegram.removeAttribute('required');
            inputMax.removeAttribute('required');

            if (tabEmail.checked) {
                inputEmail.classList.remove('hidden');
                inputEmail.setAttribute('required', 'required');
            } else if (tabTelegram.checked) {
                inputTelegram.classList.remove('hidden');
                inputTelegram.setAttribute('required', 'required');
            } else if (tabMax.checked) {
                inputMax.classList.remove('hidden');
                inputMax.setAttribute('required', 'required');
            }
        }
        tabEmail.addEventListener('change', updateForm);
        tabTelegram.addEventListener('change', updateForm);
        tabMax.addEventListener('change', updateForm);

        const cookieBanner = document.getElementById('cookie-banner');
        const acceptCookiesBtn = document.getElementById('accept-cookies');
        setTimeout(() => { cookieBanner.classList.add('show'); }, 1000);
        acceptCookiesBtn.addEventListener('click', () => { cookieBanner.classList.remove('show'); });
    </script>
</body>
</html>