/**
 * Bundled by jsDelivr using Rollup v2.79.1 and Terser v5.19.2.
 * Original file: /npm/vue@3.4.38/dist/vue.esm-bundler.js
 *
 * Do NOT use SRI with dynamically generated files! More information: https://www.jsdelivr.com/using-sri-with-dynamic-files
 */
import*as e from"@vue/runtime-dom";import{registerRuntimeCompiler as n}from"@vue/runtime-dom";export*from"@vue/runtime-dom";import{compile as t}from"@vue/compiler-dom";import{isString as o,NOOP as m,extend as r,EMPTY_OBJ as u}from"@vue/shared";
/**
* vue v3.4.38
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/const i=new WeakMap;function s(n,s){if(!o(n)){if(!n.nodeType)return m;n=n.innerHTML}const c=n,l=function(e){let n=i.get(null!=e?e:u);return n||(n=Object.create(null),i.set(null!=e?e:u,n)),n}(s),p=l[c];if(p)return p;if("#"===n[0]){const e=document.querySelector(n);n=e?e.innerHTML:""}const f=r({hoistStatic:!0,onError:void 0,onWarn:m},s);f.isCustomElement||"undefined"==typeof customElements||(f.isCustomElement=e=>!!customElements.get(e));const{code:d}=t(n,f),a=new Function("Vue",d)(e);return a._rc=!0,l[c]=a}n(s);export{s as compile};export default null;
