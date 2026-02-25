const { execSync } = require("node:child_process");

function isLinuxX64() {
  return process.platform === "linux" && process.arch === "x64";
}

if (!isLinuxX64()) {
  process.exit(0);
}

try {
  require.resolve("@rollup/rollup-linux-x64-gnu");
  process.exit(0);
} catch {
  // continue
}

try {
  execSync("npm i --no-save @rollup/rollup-linux-x64-gnu", {
    stdio: "inherit",
  });
} catch (error) {
  console.error(
    "Failed to install @rollup/rollup-linux-x64-gnu. " +
      "Your CI may be omitting optional deps or using a buggy npm version.",
  );
  throw error;
}

