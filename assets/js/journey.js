/*
 * IT Support conversational onboarding.
 * Fully scripted/state-based — no AI, no generation. Every response below
 * is predefined copy selected by a lookup on the user's choice.
 */
(function () {
    'use strict';

    var stage = document.getElementById('journey-stage');
    if (!stage) return;

    var courseLink = stage.dataset.courseLink || '/courses.php?category=it-support';
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    var lessons = [];
    try {
        var lessonsEl = document.getElementById('journey-lessons');
        if (lessonsEl) lessons = JSON.parse(lessonsEl.textContent || '[]');
    } catch (e) {
        lessons = [];
    }

    var MODULE_PATH = [
        { title: 'IT Foundations & Computer Architecture', desc: 'How computers, hardware, and operating systems actually work under the hood.' },
        { title: 'Operating Systems Administration', desc: 'Configuring, maintaining, and supporting Windows environments like a real IT professional.' },
        { title: 'Professional Troubleshooting Methodology', desc: 'A repeatable method for diagnosing real problems — not guesswork.' }
    ];

    var SCRIPT = {
        q1: {
            step: 1,
            question: 'Where are you in your IT journey?',
            subtext: 'Start by telling us where you are today.',
            options: [
                { id: 'first-job', emoji: '💼', label: 'I want my first IT job' },
                { id: 'low-pay', emoji: '💰', label: "I'm stuck in a low-paying job and want something better" },
                { id: 'already-helping', emoji: '🛠️', label: 'I already help people with technology' },
                { id: 'student', emoji: '🎓', label: "I'm a tech student" }
            ],
            responses: {
                'first-job': "That's a clear starting point.\n\nMost people trying to break into IT don't fail because they're not capable — they fail because they don't know which skills actually get you hired, or how to prove you can do the job before anyone gives you a chance.\n\nIT Support is one of the few places in tech that still hires people without a degree or years of experience, if you can show you actually know how to support real systems and real users.",
                'low-pay': "That's a common place to be, and a frustrating one.\n\nSwitching careers can feel risky, especially when you don't know if a new field will actually pay off. IT Support works as a way out because it doesn't require starting over from zero, and demand for people who can support systems and users is constant.\n\nLet's see what's actually been holding you back.",
                'already-helping': "That matters more than you probably think.\n\nA lot of people already do IT-support-shaped work informally, fixing a relative's laptop, being “the tech person” at the office, without realizing it's a real, paid skillset. The gap usually isn't ability. It's structure: knowing what to learn next and how to present what you already know.\n\nLet's find that gap.",
                'student': "Good, you already have some foundation to build on.\n\nThe problem most tech students run into isn't theory, it's the opposite: heavy on concepts, light on the hands-on troubleshooting and support skills that real IT jobs test for on day one.\n\nLet's figure out exactly where that gap is for you."
            },
            next: 'q2'
        },
        q2: {
            step: 2,
            question: "What's been getting in your way?",
            subtext: 'Be honest, this shapes what we show you next.',
            options: [
                { id: 'no-start', label: "I don't know where to start" },
                { id: 'no-skills', label: "I don't have the right skills yet" },
                { id: 'no-experience', label: 'I have skills, but no practical experience' },
                { id: 'no-clarity', label: "I don't know what companies are looking for" },
                { id: 'tried-youtube', label: "I've tried YouTube and online courses, but they didn't really help me" }
            ],
            responses: {
                'no-start': "That's the most common place to feel stuck.\n\nThere's so much conflicting advice out there, certifications, coding, networking, cloud, that “just start somewhere” can feel impossible without a clear first step.\n\nIT Support is designed to be that first step: one practical track, not ten different paths to choose between.",
                'no-skills': "That's the problem.\n\nYou can't build a better career by staying with the same skills you already have.\n\nThe good news? IT Support is a skill you can build step by step, without needing to know everything from day one.\n\nLet's figure out what you want that new skillset to do for you.",
                'no-experience': "That's a very specific, and very fixable, problem.\n\nCompanies hiring for IT Support don't just want to know you understand computers. They want proof you've actually troubleshot real problems under real conditions.\n\nThat's exactly what hands-on practice is for, not more theory, more reps.",
                'no-clarity': "That's a real blind spot, and it's not your fault, most course descriptions don't say it plainly.\n\nWhat IT Support hiring actually looks for is consistent: a troubleshooting method, comfort with Windows environments, ticketing systems, and being able to explain technical problems to non-technical people.\n\nThat's precisely what this path is built around.",
                'tried-youtube': "That's frustrating, and common. Scattered videos give you information, not a path, and no way to know if you're actually ready.\n\nWhat's usually missing isn't more content. It's structure, practice, and a clear sense of progress.\n\nLet's build that."
            },
            next: 'q3'
        },
        q3: {
            step: 3,
            question: 'What do you want this to get you?',
            subtext: "Let's make sure the path actually matches your goal.",
            options: [
                { id: 'goal-first-job', label: 'A real shot at my first IT job' },
                { id: 'goal-raise', label: 'A raise or a step up from where I am now' },
                { id: 'goal-confidence', label: 'Confidence that I actually know what I’m doing' },
                { id: 'goal-foundation', label: 'A foundation I can build on later (networking, security, etc.)' }
            ],
            responses: {
                'goal-first-job': "Then the priority isn't more theory, it's proof. A structured, practical IT Support track is exactly how people without prior experience get taken seriously.",
                'goal-raise': "Then this isn't about starting over, it's about adding a concrete, in-demand skillset on top of what you already bring to work.",
                'goal-confidence': "That comes from doing the work, not just watching it. Hands-on troubleshooting practice is what turns “I think I get it” into “I know I can do this.”",
                'goal-foundation': "Good instinct. IT Support is the foundation nearly every other IT specialization builds on. Starting here keeps every future path open."
            },
            next: 'result'
        }
    };

    var state = { current: 'q1', answers: {} };

    function el(tag, cls, html) {
        var n = document.createElement(tag);
        if (cls) n.className = cls;
        if (html !== undefined) n.innerHTML = html;
        return n;
    }

    function paragraphs(text) {
        var wrap = document.createElement('div');
        text.split('\n\n').forEach(function (p) {
            var pEl = document.createElement('p');
            pEl.textContent = p;
            wrap.appendChild(pEl);
        });
        return wrap;
    }

    function renderProgress(stepNum) {
        var wrap = el('div', 'journey-progress');
        wrap.setAttribute('role', 'presentation');
        for (var i = 1; i <= 3; i++) {
            var dot = el('span', 'journey-progress-dot' + (i < stepNum ? ' is-done' : i === stepNum ? ' is-current' : ''));
            wrap.appendChild(dot);
        }
        var label = el('span', 'journey-progress-label', 'Step ' + stepNum + ' of 3');
        wrap.appendChild(label);
        return wrap;
    }

    function transitionTo(renderFn) {
        var old = stage.querySelector('.journey-step');
        if (old && !reduceMotion) {
            old.classList.add('journey-fade-out');
            old.addEventListener('animationend', function () {
                stage.innerHTML = '';
                renderFn();
            }, { once: true });
        } else {
            stage.innerHTML = '';
            renderFn();
        }
    }

    function renderQuestion(key) {
        var def = SCRIPT[key];
        var container = el('div', 'journey-step journey-fade-in');
        container.appendChild(renderProgress(def.step));
        container.appendChild(el('h1', 'journey-question', def.question));
        container.appendChild(el('p', 'journey-subtext', def.subtext));

        var optionsWrap = el('div', 'journey-options');
        def.options.forEach(function (opt) {
            var btn = el('button', 'journey-option');
            btn.type = 'button';
            btn.setAttribute('aria-pressed', 'false');
            if (opt.emoji) {
                var em = el('span', 'journey-option-emoji', opt.emoji);
                em.setAttribute('aria-hidden', 'true');
                btn.appendChild(em);
            }
            btn.appendChild(el('span', null, opt.label));
            var check = el('span', 'journey-option-check', '✓');
            check.setAttribute('aria-hidden', 'true');
            btn.appendChild(check);

            btn.addEventListener('click', function () {
                if (optionsWrap.classList.contains('is-locked')) return;
                optionsWrap.classList.add('is-locked');
                btn.classList.add('is-selected');
                btn.setAttribute('aria-pressed', 'true');
                state.answers[key] = opt.id;
                window.setTimeout(function () {
                    renderResponse(key, opt.id);
                }, reduceMotion ? 0 : 380);
            });

            optionsWrap.appendChild(btn);
        });

        container.appendChild(optionsWrap);
        stage.appendChild(container);
        container.querySelector('h1').setAttribute('tabindex', '-1');
        container.querySelector('h1').focus();
    }

    function renderResponse(key, optionId) {
        var def = SCRIPT[key];
        transitionTo(function () {
            var container = el('div', 'journey-step journey-fade-in');
            container.appendChild(renderProgress(def.step));

            var mark = el('div', 'journey-response-mark', '✓');
            mark.setAttribute('aria-hidden', 'true');

            var response = el('div', 'journey-response');
            response.appendChild(mark);
            var textWrap = el('div', 'journey-response-text');
            textWrap.appendChild(paragraphs(def.responses[optionId]));
            response.appendChild(textWrap);

            var cta = el('button', 'journey-continue', def.next === 'result' ? 'See your path →' : 'Continue →');
            cta.type = 'button';
            cta.addEventListener('click', function () {
                transitionTo(function () {
                    if (def.next === 'result') {
                        renderResult();
                    } else {
                        renderQuestion(def.next);
                    }
                });
            });
            response.appendChild(cta);

            container.appendChild(response);
            stage.appendChild(container);
            var heading = container.querySelector('.journey-response-mark');
            heading.setAttribute('tabindex', '-1');
            heading.focus();
        });
    }

    function renderResult() {
        var container = el('div', 'journey-step journey-fade-in');

        var intro = el('div', 'journey-result-intro');
        intro.appendChild(el('div', 'journey-result-eyebrow', "YOU'VE FOUND THE RIGHT PATH"));
        intro.appendChild(el('h1', 'journey-question', 'Your IT Support Path'));
        intro.appendChild(el('p', 'journey-subtext', "We've built a practical IT Support journey to take you from where you are today to being ready for real IT work."));
        container.appendChild(intro);

        var path = el('div', 'journey-path');
        MODULE_PATH.forEach(function (m, i) {
            var step = el('div', 'journey-path-step');
            step.appendChild(el('div', 'journey-path-num', String(i + 1).padStart(2, '0')));
            var body = el('div');
            body.appendChild(el('div', 'journey-path-title', m.title));
            body.appendChild(el('div', 'journey-path-desc', m.desc));
            step.appendChild(body);
            path.appendChild(step);
        });
        var finalStep = el('div', 'journey-path-step is-final');
        finalStep.appendChild(el('div', 'journey-path-num', '✓'));
        var finalBody = el('div');
        finalBody.appendChild(el('div', 'journey-path-title', 'Ready for real IT work'));
        finalStep.appendChild(finalBody);
        path.appendChild(finalStep);
        container.appendChild(path);

        if (lessons.length) {
            var lessonsWrap = el('div', 'journey-lessons');
            lessonsWrap.appendChild(el('div', 'journey-lessons-header', 'Inside the course'));
            lessons.forEach(function (l, i) {
                var row = el('div', 'journey-lesson-row');
                row.appendChild(el('span', 'journey-lesson-index', String(i + 1).padStart(2, '0')));
                row.appendChild(el('span', 'journey-lesson-title', l.title));
                lessonsWrap.appendChild(row);
            });
            container.appendChild(lessonsWrap);
        }

        var positioning = el('div', 'journey-positioning');
        positioning.appendChild(el('h3', null, "This isn't just another course."));
        positioning.appendChild(el('p', null, "You'll learn it, practice it, and build practical IT Support skills, not just terminology."));
        container.appendChild(positioning);

        var ctaWrap = el('div', 'journey-cta-wrap');
        var cta = el('a', 'journey-cta-primary', 'Start My IT Support Journey →');
        cta.href = courseLink;
        ctaWrap.appendChild(cta);
        container.appendChild(ctaWrap);

        stage.appendChild(container);
        var h1 = container.querySelector('h1');
        h1.setAttribute('tabindex', '-1');
        h1.focus();
    }

    renderQuestion('q1');
})();
