const { execSync } = require("node:child_process");
const fs = require("node:fs");

function isLinuxX64() {
  return process.platform === "linux" && process.arch === "x64";
}

function isMuslLinux() {
  if (process.platform !== "linux") return false;
  try {
    if (fs.existsSync("/lib/ld-musl-x86_64.so.1")) return true;
  } catch {
    // ignore
  }
  return false;
}

function ensurePackage(pkg) {
  try {
    require.resolve(pkg);
    return;
  } catch {
    // continue
  }

  execSync(`npm i --no-save ${pkg}`, { stdio: "inherit" });
}

if (!isLinuxX64()) {
  process.exit(0);
}

const libc = isMuslLinux() ? "musl" : "gnu";

try {
  ensurePackage(`@rollup/rollup-linux-x64-${libc}`);
  ensurePackage(`lightningcss-linux-x64-${libc}`);
} catch (error) {
  console.error(
    "Failed to install Linux native deps for Rollup/LightningCSS. " +
      "Your CI may be omitting optional deps or using a buggy npm version.",
  );
  throw error;
}
