const PLACEHOLDER_IMAGE = "/imagenes/placeholder.svg";

export const resolveEcohotelImageUrl = (rawUrl, fallback = PLACEHOLDER_IMAGE) => {
  if (!rawUrl || typeof rawUrl !== "string") return fallback;

  const value = rawUrl.trim().replace(/\\/g, "/");
  if (!value) return fallback;

  if (value.startsWith("data:image/")) return value;

  if (value.startsWith("http://") || value.startsWith("https://")) {
    try {
      const parsed = new URL(value);
      const path = parsed.pathname || "";
      if (path.startsWith("/storage/") || path.startsWith("/imagenes/")) {
        return `${path}${parsed.search || ""}${parsed.hash || ""}`;
      }
      return value;
    } catch {
      return fallback;
    }
  }

  if (value.startsWith("//")) return `${window.location.protocol}${value}`;
  if (value.startsWith("/")) return value;

  if (value.startsWith("public/storage/")) return `/${value.replace(/^public\//, "")}`;
  if (value.startsWith("public/imagenes/")) return `/${value.replace(/^public\//, "")}`;
  if (value.startsWith("storage/")) return `/${value}`;
  if (value.startsWith("imagenes/")) return `/${value}`;
  if (value.includes("/storage/")) return value.slice(value.indexOf("/storage/"));
  if (value.includes("/imagenes/")) return value.slice(value.indexOf("/imagenes/"));

  return `/${value}`;
};

export const ECOHOTEL_IMAGE_FALLBACK = PLACEHOLDER_IMAGE;