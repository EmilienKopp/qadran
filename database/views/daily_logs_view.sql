 DROP VIEW IF EXISTS daily_logs_view;
CREATE VIEW daily_logs_view AS
SELECT
  ce.user_id,
  ce.id AS clock_entry_id,
  CONCAT_WS(' ', u.first_name, u.middle_name, u.last_name) AS "name",
  u.first_name,
  u.middle_name,
  u.last_name,
  ce.project_id,
  p.name as project_name,
  (ce.duration_seconds || ' seconds')::interval AS duration,
  TO_CHAR(
    DATE_TRUNC(
      'day',
      ce.in AT TIME ZONE 'UTC' AT TIME ZONE u.timezone
    ),
    'YYYY-MM-DD'
  ) AS date,
  ce.duration_seconds AS total_seconds
FROM
  clock_entries ce
  JOIN users u ON u.id = ce.user_id
  JOIN projects p ON p.id = ce.project_id
  LEFT JOIN activity_logs al ON al.clock_entry_id = ce.id
  LEFT JOIN activity_types at ON al.activity_type_id = at.id
GROUP BY
  ce.user_id,
  ce.id,
  ce.project_id,
  CONCAT_WS(' ', u.first_name, u.middle_name, u.last_name),
  u.first_name,
  u.middle_name,
  u.last_name,
  p.name,
  DATE_TRUNC(
    'day',
    ce.in AT TIME ZONE 'UTC' AT TIME ZONE u.timezone
  )
ORDER BY (ce.duration_seconds || ' seconds')::interval DESC 