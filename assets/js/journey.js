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

    // Single source of truth is includes/it-support-modules.php — embedded here
    // as JSON (mirrors the lessons pattern below) so the copy can't drift
    // between this page and course.php's premium template.
    var MODULE_PATH = [];
    try {
        var modulesEl = document.getElementById('journey-modules');
        if (modulesEl) MODULE_PATH = JSON.parse(modulesEl.textContent || '[]');
    } catch (e) {
        MODULE_PATH = [];
    }

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
                'first-job': "That's a clear starting point.\n\nGetting your first IT job can be frustrating when you don't know which skills to focus on — or how to prove you can actually do the work when you haven't had the chance yet.\n\nYou don't need to learn everything in IT. You need to build the right foundation and learn how to apply it in real situations.\n\nLet's figure out what's been getting in your way.",
                'low-pay': "We get it.\n\nWorking hard but feeling like your career and income aren't moving forward isn't easy.\n\nIT Support can give you a practical skillset to build a new career path — but first, let's understand where you're starting from.\n\nLet's see what's been getting in your way.",
                'already-helping': "That matters more than you probably think.\n\nYou might already be the person friends, family, or coworkers call when something stops working.\n\nThat experience is valuable — but helping people informally and working in IT professionally aren't quite the same thing.\n\nThe next step is turning what you already know into a structured skillset you can build on.\n\nLet's find that gap.",
                'student': "Good — you already have a foundation to build on.\n\nStudying technology gives you concepts and knowledge. But working in IT also means troubleshooting problems, supporting users, working with systems, and knowing what to do when things don't go as planned.\n\nThe goal is to connect what you learn with how IT actually works in practice.\n\nLet's figure out where that gap is for you."
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
                'no-start': "That's completely okay.\n\nGetting into IT can feel confusing when there are so many skills, tools, and technologies to learn.\n\nYou don't need to know everything. You need to know what to learn first.\n\nThat's where we'll start.\n\nNow let's figure out what you want that first step to lead to.",
                'no-skills': "That's the problem.\n\nYou can't build a better career by staying with the same skills you already have.\n\nThe good news? IT Support is a skill you can build step by step — without needing to know everything from day one.\n\nLet's figure out what you want that new skillset to do for you.",
                'no-experience': "That's the gap.\n\nYou may already understand the basics, but knowing something and being able to handle a real IT problem are two different things.\n\nThat's where practical experience changes things.\n\nInstead of only learning what a solution is, you need opportunities to troubleshoot, make decisions, and work through realistic IT problems.\n\nLet's figure out what you want that experience to lead to.",
                'no-clarity': "That can make job hunting confusing.\n\nYou might have certificates, watch tutorials, or study different IT topics — but still wonder whether you're learning the things employers actually look for in IT Support roles.\n\nThe goal isn't to learn everything. It's to build practical skills you can actually use at work.\n\nLet's figure out what you want your next step to be.",
                'tried-youtube': "We understand.\n\nThere's a lot of IT content online, but watching videos and finishing random courses doesn't always give you a clear path or practical experience.\n\nWhat's usually missing isn't more content. It's structure, practice, and a clear sense of progress.\n\nThat's what we're building here.\n\nLet's figure out where you want to take it."
            },
            next: 'q3'
        },
        q3: {
            step: 3,
            question: 'What do you want this to get you?',
            subtext: "Let's make sure the path actually matches your goal.",
            options: [
                { id: 'goal-first-job', label: 'A real shot at my first IT job' },
                { id: 'goal-raise', label: 'A raise or a better position' },
                { id: 'goal-confidence', label: 'Confidence that I can actually handle IT problems' },
                { id: 'goal-foundation', label: 'A foundation for where I want to go next' }
            ],
            responses: {
                'goal-first-job': "Then your goal is clear.\n\nYou need more than information — you need a foundation you can actually apply.\n\nThat's why this path focuses on practical IT Support skills, troubleshooting, and working through real-world situations.\n\nLet's show you what that path looks like.",
                'goal-raise': "Then you're looking for progress, not a complete restart.\n\nBuilding practical IT Support skills can give you another skillset to bring into your current career or use as a step toward something new.\n\nLet's show you what that path looks like.",
                'goal-confidence': "That confidence comes from practice.\n\nIt's one thing to understand a concept. It's another to face a problem and know how to approach it.\n\nThe goal is to build that confidence by learning and practicing how IT problems are actually handled.\n\nLet's show you what that path looks like.",
                'goal-foundation': "That's a strong place to start.\n\nIT Support gives you exposure to computers, operating systems, troubleshooting, users, and the environments you'll encounter in IT.\n\nFrom there, you can build toward the area you want to specialize in next.\n\nLet's show you what that path looks like."
            },
            next: 'result'
        }
    };

    var state = { current: 'q1', answers: {} };

    function el(tag, cls, text) {
        var n = document.createElement(tag);
        if (cls) n.className = cls;
        if (text !== undefined) n.textContent = text;
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
                // State-based guard: once clicked/activated, disable immediately
                // so a fast double-click/double-tap (or a second Enter/Space)
                // can't fire a second transition. Native `disabled` also blocks
                // keyboard activation after the first press, which is the point.
                if (cta.disabled) return;
                cta.disabled = true;
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
            // Move focus to the Continue button rather than the decorative,
            // aria-hidden checkmark: it's visible, meaningful, keyboard-
            // accessible, and already has a defined focus style. The response
            // text itself is announced via the stage's aria-live region.
            cta.focus();
        });
    }

    // Deterministic result personalization keyed on the existing q1/q2/q3
    // option IDs. First-match priority order, with a q3-keyed fallback that
    // covers every remaining combination. No AI/LLM, no invented facts, no
    // employment/salary claims — every line is predefined copy.
    function personalizedResultLine(answers) {
        var q1 = answers.q1, q2 = answers.q2, q3 = answers.q3;

        if (q1 === 'student') {
            return "You already have the theory. This path is where you connect it to practice — troubleshooting, systems, and the practical side of supporting real users.";
        }
        if (q1 === 'already-helping' && (q2 === 'no-experience' || q3 === 'goal-confidence')) {
            return "You already have a starting point. What's missing is structure — turning what you already know into a practical IT Support skillset you can build on.";
        }
        if (q1 === 'first-job' && q3 === 'goal-first-job') {
            return "You're starting from the beginning, so the focus is building a practical IT Support foundation — troubleshooting, systems, and real support scenarios, not just theory.";
        }
        if (q1 === 'low-pay' && q3 === 'goal-raise') {
            return "This isn't about starting over. It's about building a practical IT Support skillset that you can add to what you already know and use as your next step.";
        }

        switch (q3) {
            case 'goal-first-job':
                return "The goal is simple: build a practical IT Support foundation you can apply in real situations and continue building from.";
            case 'goal-raise':
                return "The goal is to build a practical skillset you can put to work and carry into your next opportunity.";
            case 'goal-confidence':
                return "This path is built around practice, not just theory — so you can build confidence by actually working through IT problems.";
            case 'goal-foundation':
            default:
                // 'goal-foundation', plus a defensive default (unreachable in
                // the normal flow, since q3 is always one of the 4 ids above
                // by the time renderResult() runs) so this never throws.
                return "This path gives you a practical IT Support foundation — a solid place to start as you decide where you want to go next.";
        }
    }

    function renderResult() {
        var container = el('div', 'journey-step journey-fade-in');

        var intro = el('div', 'journey-result-intro');
        intro.appendChild(el('div', 'journey-result-eyebrow', "YOU'VE FOUND THE RIGHT PATH"));
        intro.appendChild(el('h1', 'journey-question', 'Your IT Support Path'));
        intro.appendChild(el('p', 'journey-subtext', personalizedResultLine(state.answers)));
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
