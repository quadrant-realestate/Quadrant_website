import "server-only";
import { queryOne } from "@/lib/db";
import { str } from "@/lib/form";
import { saveUpload } from "@/lib/uploads";
import { attr, isInteger, isNumeric } from "./form-state";

// Laravel $request->validate() for the developments / investments / branded residences / communities /
// recognitions / floor plan forms. Rules are written exactly like the controllers:
//   { title: "required|string|max:255|unique:developments,title,5", price_from: "nullable|numeric" }
// Returns the first message per field (Laravel's default English messages), or {} when valid.

export async function validate(form: FormData, rules: Record<string, string>): Promise<Record<string, string>> {
  const errors: Record<string, string> = {};

  for (const [field, ruleString] of Object.entries(rules)) {
    const rules = ruleString.split("|");
    const value = str(form, field); // trimmed, "" → null (TrimStrings + ConvertEmptyStringsToNull)
    const name = attr(field);
    const numericField = rules.includes("numeric") || rules.includes("integer");

    if (value === null) {
      if (rules.includes("required")) errors[field] = `The ${name} field is required.`;
      continue; // other rules don't run on empty values
    }

    for (const rule of rules) {
      const [ruleName, arg = ""] = rule.split(":");
      let message: string | null = null;

      switch (ruleName) {
        case "numeric":
          if (!isNumeric(value)) message = `The ${name} field must be a number.`;
          break;
        case "integer":
          if (!isInteger(value)) message = `The ${name} field must be an integer.`;
          break;
        case "date":
          if (!isDate(value)) message = `The ${name} field must be a valid date.`;
          break;
        case "url":
          if (!isUrl(value)) message = `The ${name} field must be a valid URL.`;
          break;
        case "max": {
          const max = Number(arg);
          if (numericField) {
            if (isNumeric(value) && Number(value) > max) message = `The ${name} field must not be greater than ${max}.`;
          } else if ([...value].length > max) {
            message = `The ${name} field must not be greater than ${max} characters.`;
          }
          break;
        }
        case "unique": {
          const [table, column, ignoreId] = arg.split(",");
          const row = await queryOne(
            `SELECT id FROM \`${table}\` WHERE \`${column}\` = ?${ignoreId ? " AND id <> ?" : ""} LIMIT 1`,
            ignoreId ? [value, Number(ignoreId)] : [value],
          );
          if (row) message = `The ${name} has already been taken.`;
          break;
        }
        case "exists": {
          const [table, column] = arg.split(",");
          const row = await queryOne(`SELECT 1 AS ok FROM \`${table}\` WHERE \`${column}\` = ? LIMIT 1`, [value]);
          if (!row) message = `The selected ${name} is invalid.`;
          break;
        }
      }

      if (message) {
        errors[field] = message;
        break;
      }
    }
  }

  return errors;
}

/** PHP strtotime()-style check used by Laravel's "date" rule (common formats). */
function isDate(value: string): boolean {
  if (/^\d{4}-\d{2}-\d{2}/.test(value)) {
    const [y, m, d] = value.slice(0, 10).split("-").map(Number);
    const date = new Date(y, m - 1, d);
    return date.getFullYear() === y && date.getMonth() === m - 1 && date.getDate() === d;
  }
  return !Number.isNaN(Date.parse(value));
}

/** Laravel's "url" rule (scheme + host). */
function isUrl(value: string): boolean {
  try {
    const url = new URL(value);
    return /^[a-z][a-z0-9+.-]*:$/i.test(url.protocol) && (url.host !== "" || url.protocol === "mailto:");
  } catch {
    return false;
  }
}

/**
 * Save an upload the way these controllers named files: time() . '_' . $prefix . original name,
 * e.g. prefix "banner_" → uploads/developments/1712345678_banner_photo.jpg
 */
export async function saveUploadAs(file: File, folder: string, prefix = ""): Promise<string> {
  const renamed = prefix ? new File([file], `${prefix}${file.name}`, { type: file.type }) : file;
  return saveUpload(renamed, folder);
}
