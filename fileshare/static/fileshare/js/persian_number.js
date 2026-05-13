/**
* universal-persian-digits.js
* - Converts displayed digits 0-9 -> Persian digits (۰۱۲۳۴۵۶۷۸۹)
* - Keeps editable inputs' submitted value in ASCII by default.
* - Exclude elements by attribute/data-no-persian or class .no-persian
* - Modified: No longer modifies input/textarea/select values to prevent submission issues.
*/

(function () {
const persianDigits = ['\u06F0','\u06F1','\u06F2','\u06F3','\u06F4','\u06F5','\u06F6','\u06F7','\u06F8','\u06F9'];
const asciiDigits = ['0','1','2','3','4','5','6','7','8','9'];

function toPersianDigits(str) {
    if (!str) return str;
    return String(str).replace(/\d/g, d => persianDigits[+d]);
}
function toAsciiDigits(str) {
    if (!str) return str;
    return String(str).replace(/[\u06F0-\u06F9]/g, ch => asciiDigits[persianDigits.indexOf(ch)]);
}

// Elements / attributes to convert (safe list)
const ATTRS_TO_CONVERT = ['placeholder', 'title', 'alt', 'aria-label'];

// utility: skip element if user opted out
function shouldSkip(el) {
    if (!el) return true;
    if (el.closest && el.closest('[data-no-persian], .no-persian')) return true;
    if (el.dataset && el.dataset.preserve === 'true') return true; // data-preserve="true"
    return false;
}

// Convert text nodes under root (skips script/style)
function convertTextNodes(root) {
    const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
    acceptNode(node) {
        const parent = node.parentNode;
        if (!parent || shouldSkip(parent)) return NodeFilter.FILTER_REJECT;
        const tag = parent.nodeName && parent.nodeName.toLowerCase();
        if (['script', 'style', 'code', 'textarea', 'input'].includes(tag)) return NodeFilter.FILTER_REJECT;
        if (!/\d/.test(node.nodeValue)) return NodeFilter.FILTER_REJECT;
        return NodeFilter.FILTER_ACCEPT;
    }
    }, false);

    let n;
    while ((n = walker.nextNode())) {
    n.nodeValue = toPersianDigits(n.nodeValue);
    }
}

// Convert specific attributes like placeholder/title/alt/aria-label
function convertAttributes(root) {
    const all = root.querySelectorAll('*');
    all.forEach(el => {
    if (shouldSkip(el)) return;
    ATTRS_TO_CONVERT.forEach(attr => {
        if (el.hasAttribute && el.hasAttribute(attr)) {
        const original = el.getAttribute(attr);
        if (original && /\d/.test(original)) {
            el.setAttribute(attr, toPersianDigits(original));
        }
        }
    });
    // convert option text (does not affect submitted value, only displayed text)
    if (el.tagName && el.tagName.toLowerCase() === 'option') {
        if (!shouldSkip(el) && el.textContent && /\d/.test(el.textContent)) {
        el.textContent = toPersianDigits(el.textContent);
        }
    }
    });
}

// Handle inputs and textareas: --- MODIFIED --- no longer touches value attributes
function handleInputs(root) {
    // به‌هیچ‌وجه مقدار value المان‌های فرم را تغییر نده تا مقادیر ارسالی انگلیسی بمانند.
    return;
}

function run(root) {
    try {
    convertTextNodes(root);
    convertAttributes(root);
    handleInputs(root);
    } catch (e) {
    console.error('persian-digit-converter error', e);
    }
}

// Initial run
document.addEventListener('DOMContentLoaded', () => {
    run(document.body);

    // Observe dynamic changes
    const mo = new MutationObserver(muts => {
    muts.forEach(m => {
        if (m.type === 'childList') {
        m.addedNodes.forEach(node => {
            if (node.nodeType === Node.TEXT_NODE) {
            if (node.parentNode && !shouldSkip(node.parentNode) && /\d/.test(node.nodeValue)) {
                node.nodeValue = toPersianDigits(node.nodeValue);
            }
            } else if (node.nodeType === Node.ELEMENT_NODE) {
            run(node);
            }
        });
        } else if (m.type === 'attributes') {
        const t = m.target;
        if (!shouldSkip(t) && ATTRS_TO_CONVERT.includes(m.attributeName)) {
            const val = t.getAttribute(m.attributeName);
            if (val && /\d/.test(val)) {
            t.setAttribute(m.attributeName, toPersianDigits(val));
            }
        }
        }
    });
    });

    mo.observe(document.body, {
    childList: true,
    subtree: true,
    attributes: true,
    attributeFilter: ATTRS_TO_CONVERT
    });
});

// expose helpers for debugging / manual-run
window.PersianDigits = {
    toPersianDigits,
    toAsciiDigits,
    runOn: run
};

})();