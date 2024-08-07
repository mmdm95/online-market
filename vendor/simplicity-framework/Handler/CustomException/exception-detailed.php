<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Exception</title>

    <style>
        .hljs {
            display: block;
            padding: 0.75em;
            background: #282a36;
        }

        .hljs-keyword,
        .hljs-selector-tag,
        .hljs-literal,
        .hljs-section,
        .hljs-link {
            color: #8be9fd;
        }

        .hljs-function .hljs-keyword {
            color: #ff79c6;
        }

        .hljs,
        .hljs-subst {
            color: #f8f8f2;
        }

        .hljs-string,
        .hljs-title,
        .hljs-name,
        .hljs-type,
        .hljs-attribute,
        .hljs-symbol,
        .hljs-bullet,
        .hljs-addition,
        .hljs-variable,
        .hljs-template-tag,
        .hljs-template-variable {
            color: #f1fa8c;
        }

        .hljs-comment,
        .hljs-quote,
        .hljs-deletion,
        .hljs-meta {
            color: #6272a4;
        }

        .hljs-keyword,
        .hljs-selector-tag,
        .hljs-literal,
        .hljs-title,
        .hljs-section,
        .hljs-doctag,
        .hljs-type,
        .hljs-name,
        .hljs-strong {
            font-weight: bold;
        }

        .hljs-emphasis {
            font-style: italic;
        }
    </style>

    <style>
        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            color: #061a2b;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto 30px;
        }

        .code-table {
            background-color: #282a36;
            width: 100%;
            overflow-x: auto;
        }

        .code-lines {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -webkit-align-items: stretch;
            align-items: stretch;
            white-space: nowrap;
        }

        .code-line-number,
        .code-line-code {
            text-align: left;
            color: #fff;
            font-size: 12px;
            font-family: Consolas, Roboto, Calibri, sans-serif;
            min-height: 40px;
            padding: 10px 15px;
        }

        .code-line-number.active::before,
        .code-line-code.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, .1);
            margin: 0 -30rem;
            padding: 0 30rem;
        }

        .code-line-number {
            min-width: 70px;
            border-right: 1px solid rgba(255, 255, 255, .2);
        }

        .code-line-code {
            width: 100%;
        }

        .code-line-code pre {
            margin: 0;
        }

        .filename-header {
            background-color: #d9edf9;
            font-size: 1rem;
            padding: 10px 12px;
            cursor: pointer;
        }

        .message-container {
            border: 1px solid #eee;
            background-color: #fff;
            padding: 0 12px 12px;
            -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .message {
            background-color: #d9edf9;
            padding: 12px;
            border-radius: .325rem;
        }
    </style>
</head>
<body>

<?php

use Sim\Handler\CustomException\ExceptionFilePointer;

$sep = explode('(weirdsep)', $e->getMessage());
?>
<div class="container message-container">
    <h4>
        Error Message:
    </h4>
    <div class="message">
        <?= $sep[0]; ?>
    </div>
</div>

<?php
preg_match_all('/#\d+(?<file>[^#\(]*)\(?(?<line>\d+)\)?/m', $sep[1] ?? '', $matches);
?>
<?php if (isset($matches['file']) && count($matches['file'])): ?>
    <?php $counter = 0; ?>
    <?php foreach ($matches['file'] as $k => $trace): ?>
        <?php if('' == \trim($trace)) continue; ?>
        <?php
        $m = ExceptionFilePointer::getFilePointer($trace, $matches['line'][$k] ?? null);
        ?>

        <?php if (count($m)): ?>
            <div class="container">
                <details <?= 0 == $counter++ ? 'open="open"' : ''; ?>>
                    <summary class="filename-header">
                        <?= '"' . $e->getFile() . '" on line ' . $matches['line'][$k] ?? 'unknown'; ?>
                    </summary>
                    <div class="code-table">
                        <?php foreach ($m as $line => $code): ?>
                            <div class="code-lines">
                                <div class="code-line-number <?= $line == ($matches['line'][$k] ?? '') ? 'active' : ''; ?>">
                                    <?= $line; ?>
                                </div>
                                <div class="code-line-code <?= $line == ($matches['line'][$k] ?? '') ? 'active' : ''; ?>">
                                    <pre><?= $code; ?></pre>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </details>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <?php
    $m = ExceptionFilePointer::getFilePointer($e->getFile(), $e->getLine() ?? null);
    ?>

    <?php if (count($m)): ?>
        <div class="container">
            <details open="open">
                <summary class="filename-header">
                    <?= '"' . $e->getFile() . '" on line ' . $e->getLine() ?? 'unknown'; ?>
                </summary>
                <div class="code-table">
                    <?php foreach ($m as $line => $code): ?>
                        <div class="code-lines">
                            <div class="code-line-number <?= $line == ($e->getLine() ?? '') ? 'active' : ''; ?>">
                                <?= $line; ?>
                            </div>
                            <div class="code-line-code <?= $line == ($e->getLine() ?? '') ? 'active' : ''; ?>">
                                <pre><?= $code; ?></pre>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </details>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php foreach ($e->getTrace() as $trace): ?>
    <?php if (!isset($trace['file']) && !isset($trace['class']) && '' == \trim($trace['file']) && '' == \trim($trace['class'])) continue; ?>
    <?php
    if (!isset($trace['file']) || '' == \trim($trace['file'])) {
        $m = ExceptionFilePointer::getFilePointerFromClass($trace['class'], $trace['line'] ?? null);
    } else {
        $m = ExceptionFilePointer::getFilePointer($trace['file'], $trace['line'] ?? null);
    }
    ?>

    <?php if (count($m)): ?>
        <div class="container">
            <details>
                <summary class="filename-header">
                    <?= '"' . ($trace['file'] ?? $trace['class']) . '" on line ' . ($trace['line'] ?? 'unknown'); ?>
                </summary>
                <div class="code-table">
                    <?php foreach ($m as $line => $code): ?>
                        <div class="code-lines">
                            <div class="code-line-number <?= $line == ($trace['line'] ?? '') ? 'active' : ''; ?>">
                                <?= $line; ?>
                            </div>
                            <div class="code-line-code <?= $line == ($trace['line'] ?? '') ? 'active' : ''; ?>">
                                <pre><?= $code; ?></pre>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </details>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<script>
    (function () {
        'use strict';

        /*
        Highlight.js 10.7.2 (00233d63)
        License: BSD-3-Clause
        Copyright (c) 2006-2021, Ivan Sagalaev
        */
        var hljs = function () {
            "use strict";

            function e(t) {
                return t instanceof Map ? t.clear = t.delete = t.set = () => {
                    throw Error("map is read-only")
                } : t instanceof Set && (t.add = t.clear = t.delete = () => {
                    throw Error("set is read-only")
                }), Object.freeze(t), Object.getOwnPropertyNames(t).forEach((n => {
                    var i = t[n]
                    ;"object" != typeof i || Object.isFrozen(i) || e(i)
                })), t
            }

            var t = e, n = e;
            t.default = n
            ;

            class i {
                constructor(e) {
                    void 0 === e.data && (e.data = {}), this.data = e.data, this.isMatchIgnored = !1
                }

                ignoreMatch() {
                    this.isMatchIgnored = !0
                }
            }

            function s(e) {
                return e.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#x27;")
            }

            function a(e, ...t) {
                const n = Object.create(null);
                for (const t in e) n[t] = e[t]
                ;
                return t.forEach((e => {
                    for (const t in e) n[t] = e[t]
                })), n
            }

            const r = e => !!e.kind
            ;

            class l {
                constructor(e, t) {
                    this.buffer = "", this.classPrefix = t.classPrefix, e.walk(this)
                }

                addText(e) {
                    this.buffer += s(e)
                }

                openNode(e) {
                    if (!r(e)) return;
                    let t = e.kind
                    ;e.sublanguage || (t = `${this.classPrefix}${t}`), this.span(t)
                }

                closeNode(e) {
                    r(e) && (this.buffer += "</span>")
                }

                value() {
                    return this.buffer
                }

                span(e) {
                    this.buffer += `<span class="${e}">`
                }
            }

            class o {
                constructor() {
                    this.rootNode = {
                        children: []
                    }, this.stack = [this.rootNode]
                }

                get top() {
                    return this.stack[this.stack.length - 1]
                }

                get root() {
                    return this.rootNode
                }

                add(e) {
                    this.top.children.push(e)
                }

                openNode(e) {
                    const t = {kind: e, children: []}
                    ;this.add(t), this.stack.push(t)
                }

                closeNode() {
                    if (this.stack.length > 1) return this.stack.pop()
                }

                closeAllNodes() {
                    for (; this.closeNode();) ;
                }

                toJSON() {
                    return JSON.stringify(this.rootNode, null, 4)
                }

                walk(e) {
                    return this.constructor._walk(e, this.rootNode)
                }

                static _walk(e, t) {
                    return "string" == typeof t ? e.addText(t) : t.children && (e.openNode(t),
                        t.children.forEach((t => this._walk(e, t))), e.closeNode(t)), e
                }

                static _collapse(e) {
                    "string" != typeof e && e.children && (e.children.every((e => "string" == typeof e)) ? e.children = [e.children.join("")] : e.children.forEach((e => {
                        o._collapse(e)
                    })))
                }
            }

            class c extends o {
                constructor(e) {
                    super(), this.options = e
                }

                addKeyword(e, t) {
                    "" !== e && (this.openNode(t), this.addText(e), this.closeNode())
                }

                addText(e) {
                    "" !== e && this.add(e)
                }

                addSublanguage(e, t) {
                    const n = e.root
                    ;n.kind = t, n.sublanguage = !0, this.add(n)
                }

                toHTML() {
                    return new l(this, this.options).value()
                }

                finalize() {
                    return !0
                }
            }

            function g(e) {
                return e ? "string" == typeof e ? e : e.source : null
            }

            const u = /\[(?:[^\\\]]|\\.)*\]|\(\??|\\([1-9][0-9]*)|\\./, h = "[a-zA-Z]\\w*", d = "[a-zA-Z_]\\w*",
                f = "\\b\\d+(\\.\\d+)?", p = "(-?)(\\b0[xX][a-fA-F0-9]+|(\\b\\d+(\\.\\d*)?|\\.\\d+)([eE][-+]?\\d+)?)",
                m = "\\b(0b[01]+)", b = {
                    begin: "\\\\[\\s\\S]", relevance: 0
                }, E = {
                    className: "string", begin: "'", end: "'",
                    illegal: "\\n", contains: [b]
                }, x = {
                    className: "string", begin: '"', end: '"',
                    illegal: "\\n", contains: [b]
                }, v = {
                    begin: /\b(a|an|the|are|I'm|isn't|don't|doesn't|won't|but|just|should|pretty|simply|enough|gonna|going|wtf|so|such|will|you|your|they|like|more)\b/
                }, w = (e, t, n = {}) => {
                    const i = a({className: "comment", begin: e, end: t, contains: []}, n)
                    ;
                    return i.contains.push(v), i.contains.push({
                        className: "doctag",
                        begin: "(?:TODO|FIXME|NOTE|BUG|OPTIMIZE|HACK|XXX):", relevance: 0
                    }), i
                }, y = w("//", "$"), N = w("/\\*", "\\*/"), R = w("#", "$");
            var _ = Object.freeze({
                __proto__: null, MATCH_NOTHING_RE: /\b\B/, IDENT_RE: h, UNDERSCORE_IDENT_RE: d,
                NUMBER_RE: f, C_NUMBER_RE: p, BINARY_NUMBER_RE: m,
                RE_STARTERS_RE: "!|!=|!==|%|%=|&|&&|&=|\\*|\\*=|\\+|\\+=|,|-|-=|/=|/|:|;|<<|<<=|<=|<|===|==|=|>>>=|>>=|>=|>>>|>>|>|\\?|\\[|\\{|\\(|\\^|\\^=|\\||\\|=|\\|\\||~",
                SHEBANG: (e = {}) => {
                    const t = /^#![ ]*\//
                    ;
                    return e.binary && (e.begin = ((...e) => e.map((e => g(e))).join(""))(t, /.*\b/, e.binary, /\b.*/)),
                        a({
                            className: "meta", begin: t, end: /$/, relevance: 0, "on:begin": (e, t) => {
                                0 !== e.index && t.ignoreMatch()
                            }
                        }, e)
                }, BACKSLASH_ESCAPE: b, APOS_STRING_MODE: E,
                QUOTE_STRING_MODE: x, PHRASAL_WORDS_MODE: v, COMMENT: w, C_LINE_COMMENT_MODE: y,
                C_BLOCK_COMMENT_MODE: N, HASH_COMMENT_MODE: R, NUMBER_MODE: {
                    className: "number",
                    begin: f, relevance: 0
                }, C_NUMBER_MODE: {className: "number", begin: p, relevance: 0},
                BINARY_NUMBER_MODE: {className: "number", begin: m, relevance: 0}, CSS_NUMBER_MODE: {
                    className: "number",
                    begin: f + "(%|em|ex|ch|rem|vw|vh|vmin|vmax|cm|mm|in|pt|pc|px|deg|grad|rad|turn|s|ms|Hz|kHz|dpi|dpcm|dppx)?",
                    relevance: 0
                }, REGEXP_MODE: {
                    begin: /(?=\/[^/\n]*\/)/, contains: [{
                        className: "regexp",
                        begin: /\//, end: /\/[gimuy]*/, illegal: /\n/, contains: [b, {
                            begin: /\[/, end: /\]/,
                            relevance: 0, contains: [b]
                        }]
                    }]
                }, TITLE_MODE: {
                    className: "title", begin: h, relevance: 0
                }, UNDERSCORE_TITLE_MODE: {className: "title", begin: d, relevance: 0}, METHOD_GUARD: {
                    begin: "\\.\\s*[a-zA-Z_]\\w*", relevance: 0
                }, END_SAME_AS_BEGIN: e => Object.assign(e, {
                    "on:begin": (e, t) => {
                        t.data._beginMatch = e[1]
                    }, "on:end": (e, t) => {
                        t.data._beginMatch !== e[1] && t.ignoreMatch()
                    }
                })
            });

            function k(e, t) {
                "." === e.input[e.index - 1] && t.ignoreMatch()
            }

            function M(e, t) {
                t && e.beginKeywords && (e.begin = "\\b(" + e.beginKeywords.split(" ").join("|") + ")(?!\\.)(?=\\b|\\s)",
                    e.__beforeBegin = k, e.keywords = e.keywords || e.beginKeywords, delete e.beginKeywords,
                void 0 === e.relevance && (e.relevance = 0))
            }

            function O(e, t) {
                Array.isArray(e.illegal) && (e.illegal = ((...e) => "(" + e.map((e => g(e))).join("|") + ")")(...e.illegal))
            }

            function A(e, t) {
                if (e.match) {
                    if (e.begin || e.end) throw Error("begin & end are not supported with match")
                        ;
                    e.begin = e.match, delete e.match
                }
            }

            function L(e, t) {
                void 0 === e.relevance && (e.relevance = 1)
            }

            const I = ["of", "and", "for", "in", "not", "or", "if", "then", "parent", "list", "value"]
            ;

            function j(e, t, n = "keyword") {
                const i = {}
                ;
                return "string" == typeof e ? s(n, e.split(" ")) : Array.isArray(e) ? s(n, e) : Object.keys(e).forEach((n => {
                    Object.assign(i, j(e[n], t, n))
                })), i;

                function s(e, n) {
                    t && (n = n.map((e => e.toLowerCase()))), n.forEach((t => {
                        const n = t.split("|")
                        ;i[n[0]] = [e, B(n[0], n[1])]
                    }))
                }
            }

            function B(e, t) {
                return t ? Number(t) : (e => I.includes(e.toLowerCase()))(e) ? 0 : 1
            }

            function T(e, {plugins: t}) {
                function n(t, n) {
                    return RegExp(g(t), "m" + (e.case_insensitive ? "i" : "") + (n ? "g" : ""))
                }

                class i {
                    constructor() {
                        this.matchIndexes = {}, this.regexes = [], this.matchAt = 1, this.position = 0
                    }

                    addRule(e, t) {
                        t.position = this.position++, this.matchIndexes[this.matchAt] = t, this.regexes.push([t, e]),
                            this.matchAt += (e => RegExp(e.toString() + "|").exec("").length - 1)(e) + 1
                    }

                    compile() {
                        0 === this.regexes.length && (this.exec = () => null)
                        ;const e = this.regexes.map((e => e[1]));
                        this.matcherRe = n(((e, t = "|") => {
                            let n = 0
                            ;
                            return e.map((e => {
                                n += 1;
                                const t = n;
                                let i = g(e), s = "";
                                for (; i.length > 0;) {
                                    const e = u.exec(i);
                                    if (!e) {
                                        s += i;
                                        break
                                    }
                                    s += i.substring(0, e.index), i = i.substring(e.index + e[0].length),
                                        "\\" === e[0][0] && e[1] ? s += "\\" + (Number(e[1]) + t) : (s += e[0], "(" === e[0] && n++)
                                }
                                return s
                            })).map((e => `(${e})`)).join(t)
                        })(e), !0), this.lastIndex = 0
                    }

                    exec(e) {
                        this.matcherRe.lastIndex = this.lastIndex;
                        const t = this.matcherRe.exec(e)
                        ;
                        if (!t) return null
                            ;
                        const n = t.findIndex(((e, t) => t > 0 && void 0 !== e)), i = this.matchIndexes[n]
                        ;
                        return t.splice(0, n), Object.assign(t, i)
                    }
                }

                class s {
                    constructor() {
                        this.rules = [], this.multiRegexes = [],
                            this.count = 0, this.lastIndex = 0, this.regexIndex = 0
                    }

                    getMatcher(e) {
                        if (this.multiRegexes[e]) return this.multiRegexes[e];
                        const t = new i
                        ;
                        return this.rules.slice(e).forEach((([e, n]) => t.addRule(e, n))),
                            t.compile(), this.multiRegexes[e] = t, t
                    }

                    resumingScanAtSamePosition() {
                        return 0 !== this.regexIndex
                    }

                    considerAll() {
                        this.regexIndex = 0
                    }

                    addRule(e, t) {
                        this.rules.push([e, t]), "begin" === t.type && this.count++
                    }

                    exec(e) {
                        const t = this.getMatcher(this.regexIndex);
                        t.lastIndex = this.lastIndex
                        ;let n = t.exec(e)
                        ;
                        if (this.resumingScanAtSamePosition()) if (n && n.index === this.lastIndex) ; else {
                            const t = this.getMatcher(0);
                            t.lastIndex = this.lastIndex + 1, n = t.exec(e)
                        }
                        return n && (this.regexIndex += n.position + 1,
                        this.regexIndex === this.count && this.considerAll()), n
                    }
                }

                if (e.compilerExtensions || (e.compilerExtensions = []),
                e.contains && e.contains.includes("self")) throw Error("ERR: contains `self` is not supported at the top-level of a language.  See documentation.")
                    ;
                return e.classNameAliases = a(e.classNameAliases || {}), function t(i, r) {
                    const l = i
                    ;
                    if (i.isCompiled) return l
                        ;
                    [A].forEach((e => e(i, r))), e.compilerExtensions.forEach((e => e(i, r))),
                        i.__beforeBegin = null, [M, O, L].forEach((e => e(i, r))), i.isCompiled = !0;
                    let o = null
                    ;
                    if ("object" == typeof i.keywords && (o = i.keywords.$pattern,
                        delete i.keywords.$pattern),
                    i.keywords && (i.keywords = j(i.keywords, e.case_insensitive)),
                    i.lexemes && o) throw Error("ERR: Prefer `keywords.$pattern` to `mode.lexemes`, BOTH are not allowed. (see mode reference) ")
                        ;
                    return o = o || i.lexemes || /\w+/,
                        l.keywordPatternRe = n(o, !0), r && (i.begin || (i.begin = /\B|\b/),
                        l.beginRe = n(i.begin), i.endSameAsBegin && (i.end = i.begin),
                    i.end || i.endsWithParent || (i.end = /\B|\b/),
                    i.end && (l.endRe = n(i.end)), l.terminatorEnd = g(i.end) || "",
                    i.endsWithParent && r.terminatorEnd && (l.terminatorEnd += (i.end ? "|" : "") + r.terminatorEnd)),
                    i.illegal && (l.illegalRe = n(i.illegal)),
                    i.contains || (i.contains = []), i.contains = [].concat(...i.contains.map((e => (e => (e.variants && !e.cachedVariants && (e.cachedVariants = e.variants.map((t => a(e, {
                        variants: null
                    }, t)))), e.cachedVariants ? e.cachedVariants : S(e) ? a(e, {
                        starts: e.starts ? a(e.starts) : null
                    }) : Object.isFrozen(e) ? a(e) : e))("self" === e ? i : e)))), i.contains.forEach((e => {
                        t(e, l)
                    })), i.starts && t(i.starts, r), l.matcher = (e => {
                        const t = new s
                        ;
                        return e.contains.forEach((e => t.addRule(e.begin, {
                            rule: e, type: "begin"
                        }))), e.terminatorEnd && t.addRule(e.terminatorEnd, {
                            type: "end"
                        }), e.illegal && t.addRule(e.illegal, {type: "illegal"}), t
                    })(l), l
                }(e)
            }

            function S(e) {
                return !!e && (e.endsWithParent || S(e.starts))
            }

            function P(e) {
                const t = {
                    props: ["language", "code", "autodetect"], data: () => ({
                        detectedLanguage: "",
                        unknownLanguage: !1
                    }), computed: {
                        className() {
                            return this.unknownLanguage ? "" : "hljs " + this.detectedLanguage
                        }, highlighted() {
                            if (!this.autoDetect && !e.getLanguage(this.language)) return console.warn(`The language "${this.language}" you specified could not be found.`),
                                this.unknownLanguage = !0, s(this.code);
                            let t = {}
                            ;
                            return this.autoDetect ? (t = e.highlightAuto(this.code),
                                this.detectedLanguage = t.language) : (t = e.highlight(this.language, this.code, this.ignoreIllegals),
                                this.detectedLanguage = this.language), t.value
                        }, autoDetect() {
                            return !(this.language && (e = this.autodetect, !e && "" !== e));
                            var e
                        },
                        ignoreIllegals: () => !0
                    }, render(e) {
                        return e("pre", {}, [e("code", {
                            class: this.className, domProps: {innerHTML: this.highlighted}
                        })])
                    }
                };
                return {
                    Component: t, VuePlugin: {
                        install(e) {
                            e.component("highlightjs", t)
                        }
                    }
                }
            }

            const D = {
                "after:highlightElement": ({el: e, result: t, text: n}) => {
                    const i = H(e)
                    ;
                    if (!i.length) return;
                    const a = document.createElement("div")
                    ;a.innerHTML = t.value, t.value = ((e, t, n) => {
                        let i = 0, a = "";
                        const r = [];

                        function l() {
                            return e.length && t.length ? e[0].offset !== t[0].offset ? e[0].offset < t[0].offset ? e : t : "start" === t[0].event ? e : t : e.length ? e : t
                        }

                        function o(e) {
                            a += "<" + C(e) + [].map.call(e.attributes, (function (e) {
                                return " " + e.nodeName + '="' + s(e.value) + '"'
                            })).join("") + ">"
                        }

                        function c(e) {
                            a += "</" + C(e) + ">"
                        }

                        function g(e) {
                            ("start" === e.event ? o : c)(e.node)
                        }

                        for (; e.length || t.length;) {
                            let t = l()
                            ;
                            if (a += s(n.substring(i, t[0].offset)), i = t[0].offset, t === e) {
                                r.reverse().forEach(c)
                                ;
                                do {
                                    g(t.splice(0, 1)[0]), t = l()
                                } while (t === e && t.length && t[0].offset === i)
                                    ;
                                r.reverse().forEach(o)
                            } else "start" === t[0].event ? r.push(t[0].node) : r.pop(), g(t.splice(0, 1)[0])
                        }
                        return a + s(n.substr(i))
                    })(i, H(a), n)
                }
            };

            function C(e) {
                return e.nodeName.toLowerCase()
            }

            function H(e) {
                const t = [];
                return function e(n, i) {
                    for (let s = n.firstChild; s; s = s.nextSibling) 3 === s.nodeType ? i += s.nodeValue.length : 1 === s.nodeType && (t.push({
                        event: "start", offset: i, node: s
                    }), i = e(s, i), C(s).match(/br|hr|img|input/) || t.push({
                        event: "stop", offset: i, node: s
                    }));
                    return i
                }(e, 0), t
            }

            const $ = {}, U = e => {
                console.error(e)
            }, z = (e, ...t) => {
                console.log("WARN: " + e, ...t)
            }, K = (e, t) => {
                $[`${e}/${t}`] || (console.log(`Deprecated as of ${e}. ${t}`), $[`${e}/${t}`] = !0)
            }, G = s, V = a, W = Symbol("nomatch");
            return (e => {
                const n = Object.create(null), s = Object.create(null), a = [];
                let r = !0
                ;const l = /(^(<[^>]+>|\t|)+|\n)/gm,
                    o = "Could not find the language '{}', did you forget to load/include a language module?", g = {
                        disableAutodetect: !0, name: "Plain text", contains: []
                    };
                let u = {
                    noHighlightRe: /^(no-?highlight)$/i,
                    languageDetectRe: /\blang(?:uage)?-([\w-]+)\b/i, classPrefix: "hljs-",
                    tabReplace: null, useBR: !1, languages: null, __emitter: c
                };

                function h(e) {
                    return u.noHighlightRe.test(e)
                }

                function d(e, t, n, i) {
                    let s = "", a = ""
                    ;"object" == typeof t ? (s = e,
                        n = t.ignoreIllegals, a = t.language, i = void 0) : (K("10.7.0", "highlight(lang, code, ...args) has been deprecated."),
                        K("10.7.0", "Please use highlight(code, options) instead.\nhttps://github.com/highlightjs/highlight.js/issues/2277"),
                        a = e, s = t);
                    const r = {code: s, language: a};
                    M("before:highlight", r)
                    ;const l = r.result ? r.result : f(r.language, r.code, n, i)
                    ;
                    return l.code = r.code, M("after:highlight", l), l
                }

                function f(e, t, s, l) {
                    function c(e, t) {
                        const n = v.case_insensitive ? t[0].toLowerCase() : t[0]
                        ;
                        return Object.prototype.hasOwnProperty.call(e.keywords, n) && e.keywords[n]
                    }

                    function g() {
                        null != R.subLanguage ? (() => {
                            if ("" === M) return;
                            let e = null
                            ;
                            if ("string" == typeof R.subLanguage) {
                                if (!n[R.subLanguage]) return void k.addText(M)
                                    ;
                                e = f(R.subLanguage, M, !0, _[R.subLanguage]), _[R.subLanguage] = e.top
                            } else e = p(M, R.subLanguage.length ? R.subLanguage : null)
                            ;
                            R.relevance > 0 && (O += e.relevance), k.addSublanguage(e.emitter, e.language)
                        })() : (() => {
                            if (!R.keywords) return void k.addText(M);
                            let e = 0
                            ;R.keywordPatternRe.lastIndex = 0;
                            let t = R.keywordPatternRe.exec(M), n = "";
                            for (; t;) {
                                n += M.substring(e, t.index);
                                const i = c(R, t);
                                if (i) {
                                    const [e, s] = i
                                    ;
                                    if (k.addText(n), n = "", O += s, e.startsWith("_")) n += t[0]; else {
                                        const n = v.classNameAliases[e] || e;
                                        k.addKeyword(t[0], n)
                                    }
                                } else n += t[0]
                                ;
                                e = R.keywordPatternRe.lastIndex, t = R.keywordPatternRe.exec(M)
                            }
                            n += M.substr(e), k.addText(n)
                        })(), M = ""
                    }

                    function h(e) {
                        return e.className && k.openNode(v.classNameAliases[e.className] || e.className),
                            R = Object.create(e, {parent: {value: R}}), R
                    }

                    function d(e, t, n) {
                        let s = ((e, t) => {
                            const n = e && e.exec(t);
                            return n && 0 === n.index
                        })(e.endRe, n);
                        if (s) {
                            if (e["on:end"]) {
                                const n = new i(e);
                                e["on:end"](t, n), n.isMatchIgnored && (s = !1)
                            }
                            if (s) {
                                for (; e.endsParent && e.parent;) e = e.parent;
                                return e
                            }
                        }
                        if (e.endsWithParent) return d(e.parent, t, n)
                    }

                    function m(e) {
                        return 0 === R.matcher.regexIndex ? (M += e[0], 1) : (I = !0, 0)
                    }

                    function b(e) {
                        const n = e[0], i = t.substr(e.index), s = d(R, e, i);
                        if (!s) return W;
                        const a = R
                        ;a.skip ? M += n : (a.returnEnd || a.excludeEnd || (M += n), g(), a.excludeEnd && (M = n));
                        do {
                            R.className && k.closeNode(), R.skip || R.subLanguage || (O += R.relevance), R = R.parent
                        } while (R !== s.parent)
                            ;
                        return s.starts && (s.endSameAsBegin && (s.starts.endRe = s.endRe),
                            h(s.starts)), a.returnEnd ? 0 : n.length
                    }

                    let E = {};

                    function x(n, a) {
                        const l = a && a[0]
                        ;
                        if (M += n, null == l) return g(), 0
                            ;
                        if ("begin" === E.type && "end" === a.type && E.index === a.index && "" === l) {
                            if (M += t.slice(a.index, a.index + 1), !r) {
                                const t = Error("0 width match regex")
                                ;
                                throw t.languageName = e, t.badRule = E.rule, t
                            }
                            return 1
                        }
                        if (E = a, "begin" === a.type) return function (e) {
                            const t = e[0], n = e.rule, s = new i(n), a = [n.__beforeBegin, n["on:begin"]]
                            ;
                            for (const n of a) if (n && (n(e, s), s.isMatchIgnored)) return m(t)
                                ;
                            return n && n.endSameAsBegin && (n.endRe = RegExp(t.replace(/[-/\\^$*+?.()|[\]{}]/g, "\\$&"), "m")),
                                n.skip ? M += t : (n.excludeBegin && (M += t),
                                    g(), n.returnBegin || n.excludeBegin || (M = t)), h(n), n.returnBegin ? 0 : t.length
                        }(a)
                            ;
                        if ("illegal" === a.type && !s) {
                            const e = Error('Illegal lexeme "' + l + '" for mode "' + (R.className || "<unnamed>") + '"')
                            ;
                            throw e.mode = R, e
                        }
                        if ("end" === a.type) {
                            const e = b(a);
                            if (e !== W) return e
                        }
                        if ("illegal" === a.type && "" === l) return 1
                            ;
                        if (L > 1e5 && L > 3 * a.index) throw Error("potential infinite loop, way more iterations than matches")
                            ;
                        return M += l, l.length
                    }

                    const v = N(e)
                    ;
                    if (!v) throw U(o.replace("{}", e)), Error('Unknown language: "' + e + '"')
                        ;
                    const w = T(v, {plugins: a});
                    let y = "", R = l || w;
                    const _ = {}, k = new u.__emitter(u);
                    (() => {
                        const e = [];
                        for (let t = R; t !== v; t = t.parent) t.className && e.unshift(t.className)
                        ;
                        e.forEach((e => k.openNode(e)))
                    })();
                    let M = "", O = 0, A = 0, L = 0, I = !1;
                    try {
                        for (R.matcher.considerAll(); ;) {
                            L++, I ? I = !1 : R.matcher.considerAll(), R.matcher.lastIndex = A
                            ;const e = R.matcher.exec(t);
                            if (!e) break;
                            const n = x(t.substring(A, e.index), e)
                            ;A = e.index + n
                        }
                        return x(t.substr(A)), k.closeAllNodes(), k.finalize(), y = k.toHTML(), {
                            relevance: Math.floor(O), value: y, language: e, illegal: !1, emitter: k, top: R
                        }
                    } catch (n) {
                        if (n.message && n.message.includes("Illegal")) return {
                            illegal: !0, illegalBy: {
                                msg: n.message, context: t.slice(A - 100, A + 100), mode: n.mode
                            }, sofar: y, relevance: 0,
                            value: G(t), emitter: k
                        };
                        if (r) return {
                            illegal: !1, relevance: 0, value: G(t), emitter: k,
                            language: e, top: R, errorRaised: n
                        };
                        throw n
                    }
                }

                function p(e, t) {
                    t = t || u.languages || Object.keys(n);
                    const i = (e => {
                            const t = {
                                    relevance: 0,
                                    emitter: new u.__emitter(u), value: G(e), illegal: !1, top: g
                                }
                            ;
                            return t.emitter.addText(e), t
                        })(e), s = t.filter(N).filter(k).map((t => f(t, e, !1)))
                    ;s.unshift(i);
                    const a = s.sort(((e, t) => {
                            if (e.relevance !== t.relevance) return t.relevance - e.relevance
                                ;
                            if (e.language && t.language) {
                                if (N(e.language).supersetOf === t.language) return 1
                                    ;
                                if (N(t.language).supersetOf === e.language) return -1
                            }
                            return 0
                        })), [r, l] = a, o = r
                    ;
                    return o.second_best = l, o
                }

                const m = {
                        "before:highlightElement": ({el: e}) => {
                            u.useBR && (e.innerHTML = e.innerHTML.replace(/\n/g, "").replace(/<br[ /]*>/g, "\n"))
                        }, "after:highlightElement": ({result: e}) => {
                            u.useBR && (e.value = e.value.replace(/\n/g, "<br>"))
                        }
                    }, b = /^(<[^>]+>|\t)+/gm, E = {
                        "after:highlightElement": ({result: e}) => {
                            u.tabReplace && (e.value = e.value.replace(b, (e => e.replace(/\t/g, u.tabReplace))))
                        }
                    }
                ;

                function x(e) {
                    let t = null;
                    const n = (e => {
                            let t = e.className + " "
                            ;t += e.parentNode ? e.parentNode.className : "";
                            const n = u.languageDetectRe.exec(t)
                            ;
                            if (n) {
                                const t = N(n[1])
                                ;
                                return t || (z(o.replace("{}", n[1])), z("Falling back to no-highlight mode for this block.", e)),
                                    t ? n[1] : "no-highlight"
                            }
                            return t.split(/\s+/).find((e => h(e) || N(e)))
                        })(e)
                    ;
                    if (h(n)) return;
                    M("before:highlightElement", {el: e, language: n}), t = e
                    ;const i = t.textContent, a = n ? d(i, {language: n, ignoreIllegals: !0}) : p(i)
                    ;M("after:highlightElement", {
                        el: e, result: a, text: i
                    }), e.innerHTML = a.value, ((e, t, n) => {
                        const i = t ? s[t] : n
                        ;e.classList.add("hljs"), i && e.classList.add(i)
                    })(e, n, a.language), e.result = {
                        language: a.language, re: a.relevance, relavance: a.relevance
                    }, a.second_best && (e.second_best = {
                        language: a.second_best.language,
                        re: a.second_best.relevance, relavance: a.second_best.relevance
                    })
                }

                const v = () => {
                    v.called || (v.called = !0,
                        K("10.6.0", "initHighlighting() is deprecated.  Use highlightAll() instead."),
                        document.querySelectorAll("pre code").forEach(x))
                };
                let w = !1;

                function y() {
                    "loading" !== document.readyState ? document.querySelectorAll("pre code").forEach(x) : w = !0
                }

                function N(e) {
                    return e = (e || "").toLowerCase(), n[e] || n[s[e]]
                }

                function R(e, {languageName: t}) {
                    "string" == typeof e && (e = [e]), e.forEach((e => {
                        s[e.toLowerCase()] = t
                    }))
                }

                function k(e) {
                    const t = N(e)
                    ;
                    return t && !t.disableAutodetect
                }

                function M(e, t) {
                    const n = e;
                    a.forEach((e => {
                        e[n] && e[n](t)
                    }))
                }

                "undefined" != typeof window && window.addEventListener && window.addEventListener("DOMContentLoaded", (() => {
                    w && y()
                }), !1), Object.assign(e, {
                    highlight: d, highlightAuto: p, highlightAll: y,
                    fixMarkup: e => {
                        return K("10.2.0", "fixMarkup will be removed entirely in v11.0"), K("10.2.0", "Please see https://github.com/highlightjs/highlight.js/issues/2534"),
                            t = e,
                            u.tabReplace || u.useBR ? t.replace(l, (e => "\n" === e ? u.useBR ? "<br>" : e : u.tabReplace ? e.replace(/\t/g, u.tabReplace) : e)) : t
                            ;
                        var t
                    }, highlightElement: x,
                    highlightBlock: e => (K("10.7.0", "highlightBlock will be removed entirely in v12.0"),
                        K("10.7.0", "Please use highlightElement now."), x(e)), configure: e => {
                        e.useBR && (K("10.3.0", "'useBR' will be removed entirely in v11.0"),
                            K("10.3.0", "Please see https://github.com/highlightjs/highlight.js/issues/2559")),
                            u = V(u, e)
                    }, initHighlighting: v, initHighlightingOnLoad: () => {
                        K("10.6.0", "initHighlightingOnLoad() is deprecated.  Use highlightAll() instead."),
                            w = !0
                    }, registerLanguage: (t, i) => {
                        let s = null;
                        try {
                            s = i(e)
                        } catch (e) {
                            if (U("Language definition for '{}' could not be registered.".replace("{}", t)),
                                !r) throw e;
                            U(e), s = g
                        }
                        s.name || (s.name = t), n[t] = s, s.rawDefinition = i.bind(null, e), s.aliases && R(s.aliases, {
                            languageName: t
                        })
                    }, unregisterLanguage: e => {
                        delete n[e]
                        ;
                        for (const t of Object.keys(s)) s[t] === e && delete s[t]
                    },
                    listLanguages: () => Object.keys(n), getLanguage: N, registerAliases: R,
                    requireLanguage: e => {
                        K("10.4.0", "requireLanguage will be removed entirely in v11."),
                            K("10.4.0", "Please see https://github.com/highlightjs/highlight.js/pull/2844")
                        ;const t = N(e);
                        if (t) return t
                            ;
                        throw Error("The '{}' language is required, but not loaded.".replace("{}", e))
                    },
                    autoDetection: k, inherit: V, addPlugin: e => {
                        (e => {
                            e["before:highlightBlock"] && !e["before:highlightElement"] && (e["before:highlightElement"] = t => {
                                e["before:highlightBlock"](Object.assign({block: t.el}, t))
                            }), e["after:highlightBlock"] && !e["after:highlightElement"] && (e["after:highlightElement"] = t => {
                                e["after:highlightBlock"](Object.assign({block: t.el}, t))
                            })
                        })(e), a.push(e)
                    },
                    vuePlugin: P(e).VuePlugin
                }), e.debugMode = () => {
                    r = !1
                }, e.safeMode = () => {
                    r = !0
                }, e.versionString = "10.7.2";
                for (const e in _) "object" == typeof _[e] && t(_[e])
                ;
                return Object.assign(e, _), e.addPlugin(m), e.addPlugin(D), e.addPlugin(E), e
            })({})
        }();
        "object" == typeof exports && "undefined" != typeof module && (module.exports = hljs);
        hljs.registerLanguage("php", (() => {
            "use strict";
            return e => {
                const r = {
                    className: "variable",
                    begin: "\\$+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(?![A-Za-z0-9])(?![$])"
                }, t = {
                    className: "meta", variants: [{begin: /<\?php/, relevance: 10}, {begin: /<\?[=]?/}, {
                        begin: /\?>/
                    }]
                }, a = {
                    className: "subst", variants: [{begin: /\$\w+/}, {
                        begin: /\{\$/,
                        end: /\}/
                    }]
                }, n = e.inherit(e.APOS_STRING_MODE, {
                    illegal: null
                }), i = e.inherit(e.QUOTE_STRING_MODE, {
                    illegal: null,
                    contains: e.QUOTE_STRING_MODE.contains.concat(a)
                }), o = e.END_SAME_AS_BEGIN({
                    begin: /<<<[ \t]*(\w+)\n/, end: /[ \t]*(\w+)\b/,
                    contains: e.QUOTE_STRING_MODE.contains.concat(a)
                }), l = {
                    className: "string",
                    contains: [e.BACKSLASH_ESCAPE, t], variants: [e.inherit(n, {
                        begin: "b'", end: "'"
                    }), e.inherit(i, {begin: 'b"', end: '"'}), i, n, o]
                }, s = {
                    className: "number", variants: [{
                        begin: "\\b0b[01]+(?:_[01]+)*\\b"
                    }, {begin: "\\b0o[0-7]+(?:_[0-7]+)*\\b"}, {
                        begin: "\\b0x[\\da-f]+(?:_[\\da-f]+)*\\b"
                    }, {
                        begin: "(?:\\b\\d+(?:_\\d+)*(\\.(?:\\d+(?:_\\d+)*))?|\\B\\.\\d+)(?:e[+-]?\\d+)?"
                    }], relevance: 0
                }, c = {
                    keyword: "__CLASS__ __DIR__ __FILE__ __FUNCTION__ __LINE__ __METHOD__ __NAMESPACE__ __TRAIT__ die echo exit include include_once print require require_once array abstract and as binary bool boolean break callable case catch class clone const continue declare default do double else elseif empty enddeclare endfor endforeach endif endswitch endwhile enum eval extends final finally float for foreach from global goto if implements instanceof insteadof int integer interface isset iterable list match|0 mixed new object or private protected public real return string switch throw trait try unset use var void while xor yield",
                    literal: "false null true",
                    built_in: "Error|0 AppendIterator ArgumentCountError ArithmeticError ArrayIterator ArrayObject AssertionError BadFunctionCallException BadMethodCallException CachingIterator CallbackFilterIterator CompileError Countable DirectoryIterator DivisionByZeroError DomainException EmptyIterator ErrorException Exception FilesystemIterator FilterIterator GlobIterator InfiniteIterator InvalidArgumentException IteratorIterator LengthException LimitIterator LogicException MultipleIterator NoRewindIterator OutOfBoundsException OutOfRangeException OuterIterator OverflowException ParentIterator ParseError RangeException RecursiveArrayIterator RecursiveCachingIterator RecursiveCallbackFilterIterator RecursiveDirectoryIterator RecursiveFilterIterator RecursiveIterator RecursiveIteratorIterator RecursiveRegexIterator RecursiveTreeIterator RegexIterator RuntimeException SeekableIterator SplDoublyLinkedList SplFileInfo SplFileObject SplFixedArray SplHeap SplMaxHeap SplMinHeap SplObjectStorage SplObserver SplObserver SplPriorityQueue SplQueue SplStack SplSubject SplSubject SplTempFileObject TypeError UnderflowException UnexpectedValueException UnhandledMatchError ArrayAccess Closure Generator Iterator IteratorAggregate Serializable Stringable Throwable Traversable WeakReference WeakMap Directory __PHP_Incomplete_Class parent php_user_filter self static stdClass"
                };
                return {
                    aliases: ["php3", "php4", "php5", "php6", "php7", "php8"],
                    case_insensitive: !0, keywords: c,
                    contains: [e.HASH_COMMENT_MODE, e.COMMENT("//", "$", {
                        contains: [t]
                    }), e.COMMENT("/\\*", "\\*/", {
                        contains: [{className: "doctag", begin: "@[A-Za-z]+"}]
                    }), e.COMMENT("__halt_compiler.+?;", !1, {
                        endsWithParent: !0,
                        keywords: "__halt_compiler"
                    }), t, {className: "keyword", begin: /\$this\b/}, r, {
                        begin: /(::|->)+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/
                    }, {
                        className: "function",
                        relevance: 0, beginKeywords: "fn function", end: /[;{]/, excludeEnd: !0,
                        illegal: "[$%\\[]", contains: [{beginKeywords: "use"}, e.UNDERSCORE_TITLE_MODE, {
                            begin: "=>", endsParent: !0
                        }, {
                            className: "params", begin: "\\(", end: "\\)",
                            excludeBegin: !0, excludeEnd: !0, keywords: c,
                            contains: ["self", r, e.C_BLOCK_COMMENT_MODE, l, s]
                        }]
                    }, {
                        className: "class", variants: [{
                            beginKeywords: "enum", illegal: /[($"]/
                        }, {
                            beginKeywords: "class interface trait",
                            illegal: /[:($"]/
                        }], relevance: 0, end: /\{/, excludeEnd: !0, contains: [{
                            beginKeywords: "extends implements"
                        }, e.UNDERSCORE_TITLE_MODE]
                    }, {
                        beginKeywords: "namespace", relevance: 0, end: ";", illegal: /[.']/,
                        contains: [e.UNDERSCORE_TITLE_MODE]
                    }, {
                        beginKeywords: "use", relevance: 0, end: ";",
                        contains: [e.UNDERSCORE_TITLE_MODE]
                    }, l, s]
                }
            }
        })());

        /**
         * @see https://stackoverflow.com/questions/9899372/pure-javascript-equivalent-of-jquerys-ready-how-to-call-a-function-when-t
         * @param fn
         */
        function docReady(fn) {
            // see if DOM is already available
            if (document.readyState === "complete" || document.readyState === "interactive") {
                // call on next available tick
                setTimeout(fn, 1);
            } else {
                document.addEventListener("DOMContentLoaded", fn);
            }
        }

        docReady(function () {
            var lines, i;
            lines = document.querySelectorAll('.code-line-code');
            for (i = 0; i < lines.length; ++i) {
                hljs.highlightElement(lines[i]);
            }
            lines = document.querySelectorAll('.code-line-number');
            for (i = 0; i < lines.length; ++i) {
                hljs.highlightElement(lines[i]);
            }
        });
    })();
</script>
</body>
</html>