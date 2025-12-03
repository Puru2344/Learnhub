<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnHub - BTech CSE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; color: #333; }

        /* Navbar */
        .navbar {
            background: rgba(44, 62, 80, 0.95); backdrop-filter: blur(12px);
            padding: 15px 30px; position: fixed; top: 0; width: 100%; z-index: 1000;
            box-shadow: 0 4px 25px rgba(0,0,0,0.3);
        }
        .navbar-content {
            max-width: 1500px; margin: 0 auto; display: flex;
            justify-content: space-between; align-items: center;
        }
        .logo {
            display: flex; align-items: center; color: white; font-size: 26px; font-weight: 700; text-decoration: none;
        }
        .logo svg { width: 42px; height: 42px; margin-right: 12px; }

        .nav-links a {
            color: white; text-decoration: none; margin: 0 16px; font-weight: 600;
            padding: 10px 18px; border-radius: 30px; transition: 0.3s;
        }
        .nav-links a:hover { background: rgba(255,255,255,0.2); }

        /* Topic Search in Navbar (Right Corner) */
        .topic-search {
            background: white; border-radius: 50px; padding: 8px 15px;
            display: flex; align-items: center; box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        .topic-search input {
            border: none; outline: none; width: 260px; font-size: 15px; padding: 8px;
        }
        .topic-search button {
            background: #e74c3c; color: white; border: none; padding: 10px 18px;
            border-radius: 50px; margin-left: 10px; cursor: pointer; font-weight: bold;
        }

        .container { margin-top: 80px; display: flex; min-height: calc(100vh - 80px); }

        /* Home Page Hero */
        .hero {
            flex: 1; display: flex; flex-direction: column; justify-content: center;
            align-items: center; padding: 80px 30px; text-align: center; color: white;
        }
        .hero h1 { font-size: 52px; margin-bottom: 20px; text-shadow: 0 4px 20px rgba(0,0,0,0.4); }
        .hero p { font-size: 22px; margin-bottom: 50px; opacity: 0.95; max-width: 800px; }

        /* Subject Search on Home */
        .subject-search {
            width: 100%; max-width: 650px; position: relative;
        }
        .subject-search input {
            width: 100%; padding: 20px 70px 20px 30px; font-size: 19px;
            border: none; border-radius: 50px; outline: none;
            box-shadow: 0 15px 40px rgba(0,0,0,0.4);
        }
        .subject-search button {
            position: absolute; right: 10px; top: 10px; padding: 14px 28px;
            background: #e74c3c; color: white; border: none; border-radius: 50px;
            font-weight: bold; cursor: pointer;
        }

        .year-buttons { margin-top: 60px; display: flex; gap: 18px; flex-wrap: wrap; justify-content: center; }
        .year-btn {
            background: rgba(255,255,255,0.25); color: white; padding: 16px 34px;
            border-radius: 50px; font-size: 19px; font-weight: 600; text-decoration: none;
            border: 2px solid rgba(255,255,255,0.4); backdrop-filter: blur(10px);
            transition: all 0.4s;
        }
        .year-btn:hover { background: white; color: #667eea; transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,0.3); }

        /* Sidebar */
        .sidebar { width: 340px; background: white; padding: 30px; box-shadow: 2px 0 25px rgba(0,0,0,0.15); overflow-y: auto; }
        .sidebar h3 { color: #2c3e50; margin-bottom: 25px; font-size: 22px; text-align: center; }

        .subject-item { margin-bottom: 14px; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 18px rgba(0,0,0,0.12); }
        .subject-header {
            background: linear-gradient(135deg, #3498db, #2980b9); color: white;
            padding: 18px 22px; cursor: pointer; font-weight: 600; font-size: 18px;
        }
        .subject-header:hover { background: linear-gradient(135deg, #2980b9, #1abc9c); }
        .subject-header::after { content: '+'; float: right; font-size: 24px; }
        .subject-header.active::after { content: '−'; }
        .subject-header.highlight { background: #e74c3c !important; animation: pulse 2s infinite; }

        .topics-list { background: #f8f9fa; max-height: 0; overflow: hidden; transition: max-height 0.5s ease; }
        .topics-list.open { max-height: 3000px; }
        .topic-link {
            display: block; padding: 15px 25px; color: #2c3e50; text-decoration: none;
            border-bottom: 1px solid #eee; transition: 0.3s; font-size: 15px;
        }
        .topic-link:hover { background: #e3f2fd; padding-left: 35px; color: #1976d2; }
        .topic-link.highlight { background: #fff3cd !important; font-weight: bold; color: #d35400; }

        .main-content { flex: 1; padding: 50px; background: #f5f7fa; }
        .topic { background: white; padding: 40px; margin-bottom: 35px; border-radius: 18px; box-shadow: 0 10px 35px rgba(0,0,0,0.15); }
        .topic h2 { color: #2980b9; font-size: 30px; margin-bottom: 15px; }
        .topic h4 { color: #7f8c8d; margin-bottom: 30px; font-size: 18px; }
        .topic p { line-height: 2; font-size: 18px; color: #444; }
        .topic img { max-width: 100%; margin: 30px 0; border-radius: 14px; box-shadow: 0 12px 30px rgba(0,0,0,0.2); }

        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(231,76,60,0.7); } 70% { box-shadow: 0 0 0 20px rgba(231,76,60,0); } 100% { box-shadow: 0 0 0 0 rgba(231,76,60,0); } }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>

<!-- Navbar with Topic Search -->
<div class="navbar">
    <div class="navbar-content">
        <a href="#" onclick="showHome()" class="logo">
            <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="45" fill="#e74c3c"/>
                <path d="M30 40 L50 30 L70 40 L70 70 L50 80 L30 70 Z" fill="white"/>
                <circle cx="50" cy="50" r="15" fill="#e74c3c"/>
                <circle cx="50" cy="50" r="8" fill="white"/>
            </svg>
            LearnHub
        </a>
        <div class="nav-links">
            <a href="#" onclick="showHome()">Home</a>
            <a href="#" onclick="loadYear(1)">1st Year</a>
            <a href="#" onclick="loadYear(2)">2nd Year</a>
            <a href="#" onclick="loadYear(3)">3rd Year</a>
            <a href="#" onclick="loadYear(4)">4th Year</a>
        </div>
        <!-- Topic Search in Navbar -->
        <div class="topic-search">
            <input type="text" id="navbarTopicSearch" placeholder="Search any topic...">
            <button onclick="searchTopicFromNavbar()">Go</button>
        </div>
    </div>
</div>

<div class="container">
    <div class="sidebar" id="sidebar" style="display:none;">
        <h3>Subjects & Topics</h3>
        <div id="subjectsContainer"></div>
    </div>

    <div class="main-content" id="mainContent">
        <!-- Home Page -->
        <div class="hero">
            <h1>Welcome to LearnHub</h1>
            <p>Your complete BTech CSE study portal – Search subjects below or topics from top-right anytime!</p>

            <!-- Subject Search on Home -->
            <div class="subject-search">
                <input type="text" id="homeSubjectSearch" placeholder="Search any subject (e.g. DBMS, Chemistry, Data Structures)">
                <button onclick="searchSubjectFromHome()">Search Subject</button>
            </div>

            <div class="year-buttons">
                <a href="#" onclick="loadYear(1)" class="year-btn">1st Year</a>
                <a href="#" onclick="loadYear(2)" class="year-btn">2nd Year</a>
                <a href="#" onclick="loadYear(3)" class="year-btn">3rd Year</a>
                <a href="#" onclick="loadYear(4)" class="year-btn">4th Year</a>
            </div>
        </div>
    </div>
</div>

<script>
function showHome() {
    $('#sidebar').hide();
    $('#mainContent').html(`
        <div class="hero">
            <h1>Welcome to LearnHub</h1>
            <p>Search any subject below or use top-right search for direct topic access!</p>
            <div class="subject-search">
                <input type="text" id="homeSubjectSearch" placeholder="Search subject (e.g. DBMS, OS, Chemistry)">
                <button onclick="searchSubjectFromHome()">Search Subject</button>
            </div>
            <div class="year-buttons">
                <a href="#" onclick="loadYear(1)" class="year-btn">1st Year</a>
                <a href="#" onclick="loadYear(2)" class="year-btn">2nd Year</a>
                <a href="#" onclick="loadYear(3)" class="year-btn">3rd Year</a>
                <a href="#" onclick="loadYear(4)" class="year-btn">4th Year</a>
            </div>
        </div>
    `);
}

// Subject Search from Home
function searchSubjectFromHome() {
    const query = $('#homeSubjectSearch').val().trim();
    if (!query) return alert("Enter a subject name");
    $.get('search_subject.php', { q: query }, function(res) {
        if (!res || !res.subject_id) return alert("Subject not found!");
        loadYear(res.year_id);
        setTimeout(() => {
            $(`.subject-header:contains('${res.subject_name}')`).addClass('highlight active').next('.topics-list').addClass('open');
        }, 1000);
    }, 'json');
}

// Topic Search from Navbar
function searchTopicFromNavbar() {
    const query = $('#navbarTopicSearch').val().trim();
    if (!query) return alert("Enter a topic name");
    $.get('search_topic.php', { q: query }, function(res) {
        if (!res || !res.note_id) return alert("Topic not found: " + query);
        loadYear(res.year_id);
        setTimeout(() => {
            const $header = $(`.subject-header:contains('${res.subject_name}')`);
            $header.addClass('highlight active').next('.topics-list').addClass('open');
            const $link = $(`.topic-link:contains('${res.topic_name}')`);
            $link.addClass('highlight')[0].click();
            $('html, body').animate({ scrollTop: $link.offset().top - 150 }, 600);
        }, 1200);
    }, 'json');
}

function loadYear(yearId) {
    $('#sidebar').show();
    $('#subjectsContainer').html('<p style="text-align:center;padding:40px;color:#777;">Loading subjects...</p>');
    $.get('get_subjects_with_topics.php', { year_id: yearId, branch_id: 1 })
    .done(function(data) {
        $('#subjectsContainer').empty();
        data.forEach(sub => {
            let topics = '';
            if (sub.topics && sub.topics.length > 0) {
                sub.topics.forEach(t => {
                    let tn = t.topic_name.replace(/'/g, "\\'");
                    let sn = sub.name.replace(/'/g, "\\'");
                    topics += `<a href="#" class="topic-link" onclick="loadTopicContent(${t.id}, '${tn}', '${sn}')">${t.topic_name}</a>`;
                });
            } else {
                topics = '<div style="padding:20px;color:#95a5a6;text-align:center;font-style:italic;">No topics yet</div>';
            }
            $('#subjectsContainer').append(`
                <div class="subject-item">
                    <div class="subject-header" onclick="toggleTopics(this)">
                        ${sub.name} <small>(${sub.topics ? sub.topics.length : 0} topics)</small>
                    </div>
                    <div class="topics-list">${topics}</div>
                </div>
            `);
        });
    });
    $('#mainContent').html(`<h2 style="color:#2c3e50;padding:20px;">Year ${yearId} - Choose a topic from left</h2>`);
}

function toggleTopics(el) {
    $(el).toggleClass('active');
    $(el).next('.topics-list').toggleClass('open');
}

function loadTopicContent(id, topic, subject) {
    $('#mainContent').html(`<h2>${topic}</h2><h4 style="color:#7f8c8d;margin-bottom:30px;">${subject}</h4><div id="topicBody">Loading content...</div>`);
    $.get('get_single_note.php', { id: id }, function(note) {
        let img = note.image_path ? `<img src="${note.image_path}">` : '';
        $('#topicBody').html(`<div class="topic"><p>${note.content.replace(/\n/g, '<br>')}</p>${img}</div>`);
    }, 'json');
}
</script>
</body>
</html>