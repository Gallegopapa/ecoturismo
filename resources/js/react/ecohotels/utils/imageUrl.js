const PLACEHOLDER_IMAGE = "/imagenes/placeholder.jpg";

export const resolveEcohotelImageUrl = (rawUrl, fallback = PLACEHOLDER_IMAGE) => {
  if (!rawUrl || typeof rawUrl !== "string") return fallback;

  const value = rawUrl.trim();
  if (!value) return fallback;

  if (value.startsWith("data:image/")) return value;
  if (value.startsWith("http://") || value.startsWith("https://")) return value;
  if (value.startsWith("//")) return `${window.location.protocol}${value}`;
  if (value.startsWith("/")) return value;

  if (value.startsWith("storage/")) return `/${value}`;
  if (value.startsWith("imagenes/")) return `/${value}`;

  return `/${value}`;
};

export const ECOHOTEL_IMAGE_FALLBACK = PLACEHOLDER_IMAGE;