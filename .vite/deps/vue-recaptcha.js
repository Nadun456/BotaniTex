import {
  useHead
} from "./chunk-LR3ICREZ.js";
import "./chunk-XDQMMWU7.js";
import {
  customRef,
  getCurrentScope,
  inject,
  nextTick,
  onMounted,
  onScopeDispose,
  readonly,
  ref,
  toRef,
  warn,
  watch
} from "./chunk-7WHTAMXJ.js";
import "./chunk-DZZM6G22.js";

// node_modules/vue-recaptcha/dist/utils.mjs
function warn2(msg, ...params) {
  warn(`[vue-recaptcha]: ${msg}`, ...params);
}
function invariant(condition, msg) {
  if (!condition) {
    warn2(msg);
    throw new Error(`Invariant violation: ${msg}`);
  }
}

// node_modules/vue-recaptcha/dist/composables/context.mjs
var RecaptchaContextKey = "vue-recaptcha-context";
function useRecaptchaContext() {
  const context = inject(RecaptchaContextKey);
  if (!context) {
    warn2("You may forget to `use` vue-recaptcha plugin");
    throw new Error("useRecaptcha() is called without provider.");
  }
  return context;
}
function useRecaptchaProxy() {
  const ctx = useRecaptchaContext();
  return ctx.proxy;
}
function useAssertV2SiteKey() {
  const ctx = useRecaptchaContext();
  invariant(ctx.options.v2SiteKey, "Your config is not compatible with recaptcha v2, please provide v2SiteKey");
  return ctx.options.v2SiteKey;
}
function useAssertV3SiteKey() {
  const ctx = useRecaptchaContext();
  invariant(ctx.options.v3SiteKey, "Your config is not compatible with recaptcha v3, please provide v3SiteKey");
  return ctx.options.v3SiteKey;
}
function normalizeOptions(input) {
  var _a;
  invariant(
    input.v2SiteKey || input.v3SiteKey,
    "You didn't pass v2SiteKey or v3SiteKey to plugin, which may be a mistake"
  );
  return {
    ...input,
    loaderOptions: {
      ...input.loaderOptions,
      params: {
        ...(_a = input.loaderOptions) == null ? void 0 : _a.params,
        render: input.v3SiteKey ?? "explicit"
      }
    }
  };
}

// node_modules/p-defer/index.js
function pDefer() {
  const deferred = {};
  deferred.promise = new Promise((resolve, reject) => {
    deferred.resolve = resolve;
    deferred.reject = reject;
  });
  return deferred;
}

// node_modules/defu/dist/defu.mjs
function isPlainObject(value) {
  if (value === null || typeof value !== "object") {
    return false;
  }
  const prototype = Object.getPrototypeOf(value);
  if (prototype !== null && prototype !== Object.prototype && Object.getPrototypeOf(prototype) !== null) {
    return false;
  }
  if (Symbol.iterator in value) {
    return false;
  }
  if (Symbol.toStringTag in value) {
    return Object.prototype.toString.call(value) === "[object Module]";
  }
  return true;
}
function _defu(baseObject, defaults, namespace = ".", merger) {
  if (!isPlainObject(defaults)) {
    return _defu(baseObject, {}, namespace, merger);
  }
  const object = Object.assign({}, defaults);
  for (const key in baseObject) {
    if (key === "__proto__" || key === "constructor") {
      continue;
    }
    const value = baseObject[key];
    if (value === null || value === void 0) {
      continue;
    }
    if (merger && merger(object, key, value, namespace)) {
      continue;
    }
    if (Array.isArray(value) && Array.isArray(object[key])) {
      object[key] = [...value, ...object[key]];
    } else if (isPlainObject(value) && isPlainObject(object[key])) {
      object[key] = _defu(
        value,
        object[key],
        (namespace ? `${namespace}.` : "") + key.toString(),
        merger
      );
    } else {
      object[key] = value;
    }
  }
  return object;
}
function createDefu(merger) {
  return (...arguments_) => (
    // eslint-disable-next-line unicorn/no-array-reduce
    arguments_.reduce((p, c) => _defu(p, c, "", merger), {})
  );
}
var defu = createDefu();
var defuFn = createDefu((object, key, currentValue) => {
  if (object[key] !== void 0 && typeof currentValue === "function") {
    object[key] = currentValue(object[key]);
    return true;
  }
});
var defuArrayFn = createDefu((object, key, currentValue) => {
  if (Array.isArray(object[key]) && typeof currentValue === "function") {
    object[key] = currentValue(object[key]);
    return true;
  }
});

// node_modules/vue-recaptcha/dist/script-manager/common.mjs
function defineScriptLoader(fn) {
  return (options) => {
    return fn(normalizeScriptLoaderOptions(options));
  };
}
function normalizeScriptLoaderOptions(options) {
  return {
    ...options,
    recaptchaApiURL: options.recaptchaApiURL ?? (options.useRecaptchaNet ? "https://www.recaptcha.net/recaptcha/api.js" : "https://www.google.com/recaptcha/api.js")
  };
}
var recaptchaLoaded = pDefer();
var ONLOAD_KEY = "__vueRecaptchaLoaded";
if (typeof window !== "undefined") {
  window[ONLOAD_KEY] = () => {
    recaptchaLoaded.resolve();
  };
}
function toQueryString(params) {
  return new URLSearchParams(normalizeParams(params)).toString();
}
function normalizeParams(raw) {
  const params = defu(raw, { onload: ONLOAD_KEY, render: "explicit" });
  if (params.render === "onload") {
    warn2("passing `onload` as `render` param is not allowed");
    params.render = "explicit";
  }
  if (params.onload !== ONLOAD_KEY) {
    warn2("passing `onload` param with other value is not allowed");
    params.onload = ONLOAD_KEY;
  }
  return toStringPair(params);
}
function toStringPair(params) {
  return Object.entries(params).filter((pair) => typeof pair[1] === "string");
}
function checkRecaptchaLoad() {
  if (typeof window === "undefined") {
    return false;
  }
  const isLoaded = Object.hasOwn(window, "grecaptcha") && Object.hasOwn(window.grecaptcha, "execute");
  if (isLoaded) {
    recaptchaLoaded.resolve();
  }
  return isLoaded;
}

// node_modules/vue-recaptcha/dist/composables/proxy.mjs
function createRecaptchaProxy(isReady, getRecaptcha) {
  function assertLoaded() {
    invariant(isReady.value, "ReCAPTCHA is not loaded");
  }
  async function wait() {
    await recaptchaLoaded.promise;
    isReady.value = true;
  }
  return {
    async render(ele, options) {
      await wait();
      return getRecaptcha().render(ele, options);
    },
    reset(widgetId) {
      if (typeof widgetId === "undefined") {
        return;
      }
      assertLoaded();
      getRecaptcha().reset(widgetId);
    },
    async execute(widgetId, options) {
      if (typeof widgetId === "undefined") {
        return;
      }
      await wait();
      return getRecaptcha().execute(widgetId, options);
    }
  };
}

// node_modules/vue-recaptcha/dist/plugin.mjs
function createPlugin(scriptLoaderFactory, { getRecaptcha = () => window.grecaptcha } = {}) {
  return {
    install(app, options) {
      const isReady = ref(false);
      async function waitLoaded() {
        await recaptchaLoaded.promise;
        isReady.value = true;
      }
      waitLoaded().catch((error) => warn2("fail to load reCAPTCHA script", error));
      checkRecaptchaLoad();
      const opt = normalizeOptions(options);
      app.provide(RecaptchaContextKey, {
        isReady,
        scriptInjected: false,
        proxy: createRecaptchaProxy(isReady, getRecaptcha),
        useScriptProvider: scriptLoaderFactory(opt.loaderOptions),
        options: opt
      });
    }
  };
}

// node_modules/vue-recaptcha/dist/script-manager/unhead.mjs
var createUnheadRecaptcha = defineScriptLoader((options) => {
  return () => {
    useHead({
      link: [
        {
          key: "vue-recaptcha-google",
          rel: "preconnect",
          href: options.useRecaptchaNet ? "https://www.recaptcha.net" : "https://www.google.com"
        },
        {
          key: "vue-recaptcha-gstatic",
          rel: "preconnect",
          href: "https://www.gstatic.com",
          crossorigin: ""
        }
      ],
      script: [
        {
          key: "vue-recaptcha",
          src: `${options.recaptchaApiURL}?${toQueryString(options.params)}`,
          async: true,
          defer: true,
          nonce: options.nonce
        }
      ]
    });
  };
});

// node_modules/@vueuse/shared/index.mjs
function tryOnScopeDispose(fn) {
  if (getCurrentScope()) {
    onScopeDispose(fn);
    return true;
  }
  return false;
}
function createEventHook() {
  const fns = /* @__PURE__ */ new Set();
  const off = (fn) => {
    fns.delete(fn);
  };
  const on = (fn) => {
    fns.add(fn);
    const offFn = () => off(fn);
    tryOnScopeDispose(offFn);
    return {
      off: offFn
    };
  };
  const trigger = (...args) => {
    return Promise.all(Array.from(fns).map((fn) => fn(...args)));
  };
  return {
    on,
    off,
    trigger
  };
}
var isClient = typeof window !== "undefined" && typeof document !== "undefined";
var isWorker = typeof WorkerGlobalScope !== "undefined" && globalThis instanceof WorkerGlobalScope;
var noop = () => {
};
var isIOS = getIsIOS();
function getIsIOS() {
  var _a, _b;
  return isClient && ((_a = window == null ? void 0 : window.navigator) == null ? void 0 : _a.userAgent) && (/iP(?:ad|hone|od)/.test(window.navigator.userAgent) || ((_b = window == null ? void 0 : window.navigator) == null ? void 0 : _b.maxTouchPoints) > 2 && /iPad|Macintosh/.test(window == null ? void 0 : window.navigator.userAgent));
}
function cacheStringFunction(fn) {
  const cache = /* @__PURE__ */ Object.create(null);
  return (str) => {
    const hit = cache[str];
    return hit || (cache[str] = fn(str));
  };
}
var hyphenateRE = /\B([A-Z])/g;
var hyphenate = cacheStringFunction((str) => str.replace(hyphenateRE, "-$1").toLowerCase());
var camelizeRE = /-(\w)/g;
var camelize = cacheStringFunction((str) => {
  return str.replace(camelizeRE, (_, c) => c ? c.toUpperCase() : "");
});
function toRef2(...args) {
  if (args.length !== 1)
    return toRef(...args);
  const r = args[0];
  return typeof r === "function" ? readonly(customRef(() => ({ get: r, set: noop }))) : ref(r);
}
function whenever(source, cb, options) {
  const stop = watch(
    source,
    (v, ov, onInvalidate) => {
      if (v) {
        if (options == null ? void 0 : options.once)
          nextTick(() => stop());
        cb(v, ov, onInvalidate);
      }
    },
    {
      ...options,
      once: false
    }
  );
  return stop;
}

// node_modules/vue-recaptcha/dist/composables/challenge-v2.mjs
var RecaptchaV2State = ((RecaptchaV2State2) => {
  RecaptchaV2State2["Init"] = "init";
  RecaptchaV2State2["Verified"] = "verified";
  RecaptchaV2State2["Expired"] = "expired";
  RecaptchaV2State2["Error"] = "error";
  return RecaptchaV2State2;
})(RecaptchaV2State || {});
function useChallengeV2({ root = ref(), options = {} }) {
  const siteKey = useAssertV2SiteKey();
  const widgetID = ref();
  const proxy = useRecaptchaProxy();
  const verify = createEventHook();
  const expired = createEventHook();
  const error = createEventHook();
  const rootRef = toRef2(root);
  const state = ref(
    "init"
    /* Init */
  );
  whenever(rootRef, async (el) => {
    const id = await proxy.render(el, {
      ...options,
      sitekey: siteKey,
      // eslint-disable-next-line @typescript-eslint/no-misused-promises
      callback: verify.trigger,
      // eslint-disable-next-line @typescript-eslint/no-misused-promises
      "expired-callback": expired.trigger,
      // eslint-disable-next-line @typescript-eslint/no-misused-promises
      "error-callback": error.trigger
    });
    widgetID.value = id;
  });
  verify.on(() => {
    state.value = "verified";
  });
  expired.on(() => {
    state.value = "expired";
  });
  error.on(() => {
    state.value = "error";
  });
  return {
    root: rootRef,
    widgetID,
    execute() {
      if (typeof widgetID.value !== "undefined") {
        proxy.execute(widgetID.value);
      }
    },
    reset() {
      state.value = "init";
      if (typeof widgetID.value !== "undefined") {
        proxy.reset(widgetID.value);
      }
    },
    state,
    onVerify: verify.on,
    onExpired: expired.on,
    onError: error.on
  };
}

// node_modules/vue-recaptcha/dist/composables/challenge-v3.mjs
function useChallengeV3(action) {
  const siteKey = useAssertV3SiteKey();
  const proxy = useRecaptchaProxy();
  const response = ref();
  return {
    response,
    async execute() {
      return response.value = await proxy.execute(siteKey, { action });
    }
  };
}

// node_modules/vue-recaptcha/dist/composables/script-provider.mjs
function useRecaptchaProvider() {
  const ctx = useRecaptchaContext();
  if (ctx.scriptInjected) {
    warn2("`useRecaptchaProvider` is used multiple time");
  } else {
    ctx.scriptInjected = true;
    ctx.useScriptProvider();
    onMounted(() => {
      checkRecaptchaLoad();
    });
  }
}

// node_modules/vue-recaptcha/dist/api.mjs
import { default as default2 } from "E:/BotaniTex/node_modules/vue-recaptcha/dist/components/ChallengeV2.vue";
import { default as default3 } from "E:/BotaniTex/node_modules/vue-recaptcha/dist/components/ChallengeV3.vue";
import { default as default4 } from "E:/BotaniTex/node_modules/vue-recaptcha/dist/components/Checkbox.vue";

// node_modules/vue-recaptcha/dist/index.mjs
var plugin = createPlugin(createUnheadRecaptcha);
export {
  default2 as ChallengeV2,
  default3 as ChallengeV3,
  default4 as Checkbox,
  RecaptchaV2State,
  plugin as VueRecaptchaPlugin,
  createPlugin,
  plugin as default,
  defineScriptLoader,
  toQueryString,
  useChallengeV2,
  useChallengeV3,
  useRecaptchaContext,
  useRecaptchaProvider,
  useRecaptchaProxy
};
//# sourceMappingURL=vue-recaptcha.js.map
