<?php

if (!class_exists("Tgc_StrongMail_GetSingleSignOnURLRequest", false)) {
/**
 * Tgc_StrongMail_GetSingleSignOnURLRequest
 */
class Tgc_StrongMail_GetSingleSignOnURLRequest {
}}

if (!class_exists("Tgc_StrongMail_connectionInfo", false)) {
/**
 * Tgc_StrongMail_connectionInfo
 */
class Tgc_StrongMail_connectionInfo {
	/**
	 * @access public
	 * @var string
	 */
	public $databaseName;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DatabaseType
	 */
	public $databaseType;
	/**
	 * @access public
	 * @var string
	 */
	public $hostname;
	/**
	 * @access public
	 * @var string
	 */
	public $password;
	/**
	 * @access public
	 * @var string
	 */
	public $port;
	/**
	 * @access public
	 * @var string
	 */
	public $username;
}}

if (!class_exists("Tgc_StrongMail_hourlyRefresh", false)) {
/**
 * Tgc_StrongMail_hourlyRefresh
 */
class Tgc_StrongMail_hourlyRefresh {
	/**
	 * @access public
	 * @var Tgc_StrongMail_HourlyInterval
	 */
	public $interval;
}}

if (!class_exists("Tgc_StrongMail_dailyRefresh", false)) {
/**
 * Tgc_StrongMail_dailyRefresh
 */
class Tgc_StrongMail_dailyRefresh {
	/**
	 * @access public
	 * @var time
	 */
	public $startTime;
}}

if (!class_exists("Tgc_StrongMail_weeklyRefresh", false)) {
/**
 * Tgc_StrongMail_weeklyRefresh
 */
class Tgc_StrongMail_weeklyRefresh {
	/**
	 * @access public
	 * @var time
	 */
	public $startTime;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DayOfWeek[]
	 */
	public $day;
}}

if (!class_exists("Tgc_StrongMail_DataSourceType", false)) {
/**
 * Tgc_StrongMail_DataSourceType
 */
class Tgc_StrongMail_DataSourceType {
}}

if (!class_exists("Tgc_StrongMail_DatabaseType", false)) {
/**
 * Tgc_StrongMail_DatabaseType
 */
class Tgc_StrongMail_DatabaseType {
}}

if (!class_exists("Tgc_StrongMail_DataSourceField", false)) {
/**
 * Tgc_StrongMail_DataSourceField
 */
class Tgc_StrongMail_DataSourceField {
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceDataType
	 */
	public $dataType;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceFieldType
	 */
	public $fieldType;
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var bool
	 */
	public $isPrimaryKey;
	/**
	 * @access public
	 * @var bool
	 */
	public $writebackEnabled;
}}

if (!class_exists("Tgc_StrongMail_DataSourceFieldType", false)) {
/**
 * Tgc_StrongMail_DataSourceFieldType
 */
class Tgc_StrongMail_DataSourceFieldType {
}}

if (!class_exists("Tgc_StrongMail_DataSourceDataType", false)) {
/**
 * Tgc_StrongMail_DataSourceDataType
 */
class Tgc_StrongMail_DataSourceDataType {
}}

if (!class_exists("Tgc_StrongMail_DataSourceRecord", false)) {
/**
 * Tgc_StrongMail_DataSourceRecord
 */
class Tgc_StrongMail_DataSourceRecord {
	/**
	 * @access public
	 * @var Tgc_StrongMail_NameValuePair[]
	 */
	public $field;
}}

if (!class_exists("Tgc_StrongMail_DataSourceOperationStatus", false)) {
/**
 * Tgc_StrongMail_DataSourceOperationStatus
 */
class Tgc_StrongMail_DataSourceOperationStatus {
}}

if (!class_exists("Tgc_StrongMail_DataSourceDedupeOption", false)) {
/**
 * Tgc_StrongMail_DataSourceDedupeOption
 */
class Tgc_StrongMail_DataSourceDedupeOption {
}}

if (!class_exists("Tgc_StrongMail_DataSourceOrderBy", false)) {
/**
 * Tgc_StrongMail_DataSourceOrderBy
 */
class Tgc_StrongMail_DataSourceOrderBy {
}}

if (!class_exists("Tgc_StrongMail_RefreshRecordsRequest", false)) {
/**
 * Tgc_StrongMail_RefreshRecordsRequest
 */
class Tgc_StrongMail_RefreshRecordsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ExternalDataSourceId
	 */
	public $dataSourceId;
}}

if (!class_exists("Tgc_StrongMail_CancelRefreshRecordsRequest", false)) {
/**
 * Tgc_StrongMail_CancelRefreshRecordsRequest
 */
class Tgc_StrongMail_CancelRefreshRecordsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ExternalDataSourceId
	 */
	public $dataSourceId;
}}

if (!class_exists("Tgc_StrongMail_TargetType", false)) {
/**
 * Tgc_StrongMail_TargetType
 */
class Tgc_StrongMail_TargetType {
}}

if (!class_exists("Tgc_StrongMail_TargetOrderBy", false)) {
/**
 * Tgc_StrongMail_TargetOrderBy
 */
class Tgc_StrongMail_TargetOrderBy {
}}

if (!class_exists("Tgc_StrongMail_SuppressionListOrderBy", false)) {
/**
 * Tgc_StrongMail_SuppressionListOrderBy
 */
class Tgc_StrongMail_SuppressionListOrderBy {
}}

if (!class_exists("Tgc_StrongMail_SeedListOrderBy", false)) {
/**
 * Tgc_StrongMail_SeedListOrderBy
 */
class Tgc_StrongMail_SeedListOrderBy {
}}

if (!class_exists("Tgc_StrongMail_TrackingTag", false)) {
/**
 * Tgc_StrongMail_TrackingTag
 */
class Tgc_StrongMail_TrackingTag {
}}

if (!class_exists("Tgc_StrongMail_OpenTag", false)) {
/**
 * Tgc_StrongMail_OpenTag
 */
class Tgc_StrongMail_OpenTag {
}}

if (!class_exists("Tgc_StrongMail_TrackingTagProperties", false)) {
/**
 * Tgc_StrongMail_TrackingTagProperties
 */
class Tgc_StrongMail_TrackingTagProperties {
	/**
	 * @access public
	 * @var string
	 */
	public $title;
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var string
	 */
	public $offerUrl;
	/**
	 * @access public
	 * @var string
	 */
	public $imageUrl;
}}

if (!class_exists("Tgc_StrongMail_NamedLink", false)) {
/**
 * Tgc_StrongMail_NamedLink
 */
class Tgc_StrongMail_NamedLink {
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var string
	 */
	public $url;
	/**
	 * @access public
	 * @var string
	 */
	public $linkId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_TrackingTag
	 */
	public $trackingTag;
	/**
	 * @access public
	 * @var Tgc_StrongMail_TrackingTagProperties
	 */
	public $trackingTagProperties;
	/**
	 * @access public
	 * @var Tgc_StrongMail_WebAnalytics
	 */
	public $webAnalytics;
}}

if (!class_exists("Tgc_StrongMail_MessagePart", false)) {
/**
 * Tgc_StrongMail_MessagePart
 */
class Tgc_StrongMail_MessagePart {
	/**
	 * @access public
	 * @var string
	 */
	public $content;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MessageFormat
	 */
	public $format;
	/**
	 * @access public
	 * @var string
	 */
	public $mediaServerFolderName;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MediaServerId
	 */
	public $mediaServerId;
	/**
	 * @access public
	 * @var bool
	 */
	public $isXsl;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OpenTag
	 */
	public $openTag;
	/**
	 * @access public
	 * @var Tgc_StrongMail_NamedLink[]
	 */
	public $namedLinks;
}}

if (!class_exists("Tgc_StrongMail_MessageFormat", false)) {
/**
 * Tgc_StrongMail_MessageFormat
 */
class Tgc_StrongMail_MessageFormat {
}}

if (!class_exists("Tgc_StrongMail_MessageType", false)) {
/**
 * Tgc_StrongMail_MessageType
 */
class Tgc_StrongMail_MessageType {
}}

if (!class_exists("Tgc_StrongMail_TemplateOrderBy", false)) {
/**
 * Tgc_StrongMail_TemplateOrderBy
 */
class Tgc_StrongMail_TemplateOrderBy {
}}

if (!class_exists("Tgc_StrongMail_ImportMessagePartRequest", false)) {
/**
 * Tgc_StrongMail_ImportMessagePartRequest
 */
class Tgc_StrongMail_ImportMessagePartRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_TemplateId
	 */
	public $templateId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MediaServerId
	 */
	public $mediaServerId;
	/**
	 * @access public
	 * @var bool
	 */
	public $isXsl;
	/**
	 * @access public
	 * @var string
	 */
	public $folderName;
	/**
	 * @access public
	 * @var base64Binary
	 */
	public $zipFile;
}}

if (!class_exists("Tgc_StrongMail_ValidateXslRequest", false)) {
/**
 * Tgc_StrongMail_ValidateXslRequest
 */
class Tgc_StrongMail_ValidateXslRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_TemplateId
	 */
	public $templateId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MessageFormat
	 */
	public $messageFormat;
}}

if (!class_exists("Tgc_StrongMail_FetchLinksRequest", false)) {
/**
 * Tgc_StrongMail_FetchLinksRequest
 */
class Tgc_StrongMail_FetchLinksRequest {
}}

if (!class_exists("Tgc_StrongMail_FetchLinksTemplateRequest", false)) {
/**
 * Tgc_StrongMail_FetchLinksTemplateRequest
 */
class Tgc_StrongMail_FetchLinksTemplateRequest extends Tgc_StrongMail_FetchLinksRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_Template
	 */
	public $template;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MessageFormat
	 */
	public $messageFormat;
}}

if (!class_exists("Tgc_StrongMail_ContentBlockToken", false)) {
/**
 * Tgc_StrongMail_ContentBlockToken
 */
class Tgc_StrongMail_ContentBlockToken {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ContentBlockId
	 */
	public $contentBlockId;
	/**
	 * @access public
	 * @var string
	 */
	public $token;
}}

if (!class_exists("Tgc_StrongMail_ContentBlockOrderBy", false)) {
/**
 * Tgc_StrongMail_ContentBlockOrderBy
 */
class Tgc_StrongMail_ContentBlockOrderBy {
}}

if (!class_exists("Tgc_StrongMail_FetchLinksContentBlockRequest", false)) {
/**
 * Tgc_StrongMail_FetchLinksContentBlockRequest
 */
class Tgc_StrongMail_FetchLinksContentBlockRequest extends Tgc_StrongMail_FetchLinksRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ContentBlock
	 */
	public $contentBlock;
}}

if (!class_exists("Tgc_StrongMail_AttachmentOrderBy", false)) {
/**
 * Tgc_StrongMail_AttachmentOrderBy
 */
class Tgc_StrongMail_AttachmentOrderBy {
}}

if (!class_exists("Tgc_StrongMail_RuleValue", false)) {
/**
 * Tgc_StrongMail_RuleValue
 */
class Tgc_StrongMail_RuleValue {
}}

if (!class_exists("Tgc_StrongMail_ColumnRuleValue", false)) {
/**
 * Tgc_StrongMail_ColumnRuleValue
 */
class Tgc_StrongMail_ColumnRuleValue extends Tgc_StrongMail_RuleValue {
	/**
	 * @access public
	 * @var string
	 */
	public $value;
}}

if (!class_exists("Tgc_StrongMail_ContentBlockTokenRuleValue", false)) {
/**
 * Tgc_StrongMail_ContentBlockTokenRuleValue
 */
class Tgc_StrongMail_ContentBlockTokenRuleValue extends Tgc_StrongMail_RuleValue {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ContentBlockToken
	 */
	public $value;
}}

if (!class_exists("Tgc_StrongMail_TextRuleValue", false)) {
/**
 * Tgc_StrongMail_TextRuleValue
 */
class Tgc_StrongMail_TextRuleValue extends Tgc_StrongMail_RuleValue {
	/**
	 * @access public
	 * @var string
	 */
	public $value;
}}

if (!class_exists("Tgc_StrongMail_NestedRuleRuleValue", false)) {
/**
 * Tgc_StrongMail_NestedRuleRuleValue
 */
class Tgc_StrongMail_NestedRuleRuleValue extends Tgc_StrongMail_RuleValue {
	/**
	 * @access public
	 * @var Tgc_StrongMail_RuleId
	 */
	public $value;
}}

if (!class_exists("Tgc_StrongMail_RuleIfPartCondition", false)) {
/**
 * Tgc_StrongMail_RuleIfPartCondition
 */
class Tgc_StrongMail_RuleIfPartCondition {
	/**
	 * @access public
	 * @var string
	 */
	public $column;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ComparisonOperation
	 */
	public $op;
	/**
	 * @access public
	 * @var string
	 */
	public $value;
}}

if (!class_exists("Tgc_StrongMail_RuleIfPart", false)) {
/**
 * Tgc_StrongMail_RuleIfPart
 */
class Tgc_StrongMail_RuleIfPart {
	/**
	 * @access public
	 * @var Tgc_StrongMail_RuleIfPartCondition
	 */
	public $condition1;
	/**
	 * @access public
	 * @var Tgc_StrongMail_LogicalOperation
	 */
	public $logicalOperation;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RuleIfPartCondition
	 */
	public $condition;
}}

if (!class_exists("Tgc_StrongMail_RuleThenPart", false)) {
/**
 * Tgc_StrongMail_RuleThenPart
 */
class Tgc_StrongMail_RuleThenPart {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ColumnRuleValue
	 */
	public $columnRuleValue;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ContentBlockTokenRuleValue
	 */
	public $contentBlockTokenRuleValue;
	/**
	 * @access public
	 * @var Tgc_StrongMail_TextRuleValue
	 */
	public $textRuleValue;
}}

if (!class_exists("Tgc_StrongMail_RuleElsePart", false)) {
/**
 * Tgc_StrongMail_RuleElsePart
 */
class Tgc_StrongMail_RuleElsePart {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ColumnRuleValue
	 */
	public $columnRuleValue;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ContentBlockTokenRuleValue
	 */
	public $contentBlockTokenRuleValue;
	/**
	 * @access public
	 * @var Tgc_StrongMail_NestedRuleRuleValue
	 */
	public $nestedRuleRuleValue;
	/**
	 * @access public
	 * @var Tgc_StrongMail_TextRuleValue
	 */
	public $textRuleValue;
}}

if (!class_exists("Tgc_StrongMail_RuleOrderBy", false)) {
/**
 * Tgc_StrongMail_RuleOrderBy
 */
class Tgc_StrongMail_RuleOrderBy {
}}

if (!class_exists("Tgc_StrongMail_schedule", false)) {
/**
 * Tgc_StrongMail_schedule
 */
class Tgc_StrongMail_schedule {
	/**
	 * @access public
	 * @var dateTime
	 */
	public $startDateTime;
	/**
	 * @access public
	 * @var anyType
	 */
	public $recurrence;
	/**
	 * @access public
	 * @var date
	 */
	public $endDate;
	/**
	 * @access public
	 * @var integer
	 */
	public $endAfterXMailings;
	/**
	 * @access public
	 * @var anyType
	 */
	public $minutelyRecurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MinutelyInterval
	 */
	public $interval;
	/**
	 * @access public
	 * @var anyType
	 */
	public $hourlyRecurrence;
	/**
	 * @access public
	 * @var anyType
	 */
	public $dailyRecurrence;
	/**
	 * @access public
	 * @var bool
	 */
	public $everyWeekDay;
	/**
	 * @access public
	 * @var anyType
	 */
	public $weeklyRecurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DayOfWeek[]
	 */
	public $day;
	/**
	 * @access public
	 * @var anyType
	 */
	public $monthlyByDateRecurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DayOfMonth[]
	 */
	public $dayOfMonth;
	/**
	 * @access public
	 * @var anyType
	 */
	public $monthlyByDayRecurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_WeeklyOccurrence
	 */
	public $weeklyOccurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DailyOccurrence
	 */
	public $dailyOccurrence;
	/**
	 * @access public
	 * @var anyType
	 */
	public $yearlyByDateRecurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_Month
	 */
	public $month;
	/**
	 * @access public
	 * @var anyType
	 */
	public $yearlyByDayRecurrence;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $nextScheduledDateTime;
}}

if (!class_exists("Tgc_StrongMail_recurrence", false)) {
/**
 * Tgc_StrongMail_recurrence
 */
class Tgc_StrongMail_recurrence {
	/**
	 * @access public
	 * @var date
	 */
	public $endDate;
	/**
	 * @access public
	 * @var integer
	 */
	public $endAfterXMailings;
	/**
	 * @access public
	 * @var anyType
	 */
	public $minutelyRecurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MinutelyInterval
	 */
	public $interval;
	/**
	 * @access public
	 * @var anyType
	 */
	public $hourlyRecurrence;
	/**
	 * @access public
	 * @var anyType
	 */
	public $dailyRecurrence;
	/**
	 * @access public
	 * @var bool
	 */
	public $everyWeekDay;
	/**
	 * @access public
	 * @var anyType
	 */
	public $weeklyRecurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DayOfWeek[]
	 */
	public $day;
	/**
	 * @access public
	 * @var anyType
	 */
	public $monthlyByDateRecurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DayOfMonth[]
	 */
	public $dayOfMonth;
	/**
	 * @access public
	 * @var anyType
	 */
	public $monthlyByDayRecurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_WeeklyOccurrence
	 */
	public $weeklyOccurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DailyOccurrence
	 */
	public $dailyOccurrence;
	/**
	 * @access public
	 * @var anyType
	 */
	public $yearlyByDateRecurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_Month
	 */
	public $month;
	/**
	 * @access public
	 * @var anyType
	 */
	public $yearlyByDayRecurrence;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $nextScheduledDateTime;
}}

if (!class_exists("Tgc_StrongMail_minutelyRecurrence", false)) {
/**
 * Tgc_StrongMail_minutelyRecurrence
 */
class Tgc_StrongMail_minutelyRecurrence {
	/**
	 * @access public
	 * @var Tgc_StrongMail_MinutelyInterval
	 */
	public $interval;
}}

if (!class_exists("Tgc_StrongMail_hourlyRecurrence", false)) {
/**
 * Tgc_StrongMail_hourlyRecurrence
 */
class Tgc_StrongMail_hourlyRecurrence {
	/**
	 * @access public
	 * @var Tgc_StrongMail_HourlyInterval
	 */
	public $interval;
}}

if (!class_exists("Tgc_StrongMail_dailyRecurrence", false)) {
/**
 * Tgc_StrongMail_dailyRecurrence
 */
class Tgc_StrongMail_dailyRecurrence {
	/**
	 * @access public
	 * @var Tgc_StrongMail_DailyInterval
	 */
	public $interval;
	/**
	 * @access public
	 * @var bool
	 */
	public $everyWeekDay;
}}

if (!class_exists("Tgc_StrongMail_weeklyRecurrence", false)) {
/**
 * Tgc_StrongMail_weeklyRecurrence
 */
class Tgc_StrongMail_weeklyRecurrence {
	/**
	 * @access public
	 * @var Tgc_StrongMail_WeeklyInterval
	 */
	public $interval;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DayOfWeek[]
	 */
	public $day;
}}

if (!class_exists("Tgc_StrongMail_monthlyByDateRecurrence", false)) {
/**
 * Tgc_StrongMail_monthlyByDateRecurrence
 */
class Tgc_StrongMail_monthlyByDateRecurrence {
	/**
	 * @access public
	 * @var Tgc_StrongMail_MonthlyInterval
	 */
	public $interval;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DayOfMonth[]
	 */
	public $dayOfMonth;
}}

if (!class_exists("Tgc_StrongMail_monthlyByDayRecurrence", false)) {
/**
 * Tgc_StrongMail_monthlyByDayRecurrence
 */
class Tgc_StrongMail_monthlyByDayRecurrence {
	/**
	 * @access public
	 * @var Tgc_StrongMail_MonthlyInterval
	 */
	public $interval;
	/**
	 * @access public
	 * @var Tgc_StrongMail_WeeklyOccurrence
	 */
	public $weeklyOccurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DailyOccurrence
	 */
	public $dailyOccurrence;
}}

if (!class_exists("Tgc_StrongMail_yearlyByDateRecurrence", false)) {
/**
 * Tgc_StrongMail_yearlyByDateRecurrence
 */
class Tgc_StrongMail_yearlyByDateRecurrence {
	/**
	 * @access public
	 * @var Tgc_StrongMail_Month
	 */
	public $month;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DayOfMonth
	 */
	public $day;
}}

if (!class_exists("Tgc_StrongMail_yearlyByDayRecurrence", false)) {
/**
 * Tgc_StrongMail_yearlyByDayRecurrence
 */
class Tgc_StrongMail_yearlyByDayRecurrence {
	/**
	 * @access public
	 * @var Tgc_StrongMail_Month
	 */
	public $month;
	/**
	 * @access public
	 * @var Tgc_StrongMail_WeeklyOccurrence
	 */
	public $weeklyOccurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DailyOccurrence
	 */
	public $dailyOccurrence;
}}

if (!class_exists("Tgc_StrongMail_MailingStatus", false)) {
/**
 * Tgc_StrongMail_MailingStatus
 */
class Tgc_StrongMail_MailingStatus {
}}

if (!class_exists("Tgc_StrongMail_MailingType", false)) {
/**
 * Tgc_StrongMail_MailingType
 */
class Tgc_StrongMail_MailingType {
}}

if (!class_exists("Tgc_StrongMail_MailingPriority", false)) {
/**
 * Tgc_StrongMail_MailingPriority
 */
class Tgc_StrongMail_MailingPriority {
}}

if (!class_exists("Tgc_StrongMail_MinutelyInterval", false)) {
/**
 * Tgc_StrongMail_MinutelyInterval
 */
class Tgc_StrongMail_MinutelyInterval {
}}

if (!class_exists("Tgc_StrongMail_HourlyInterval", false)) {
/**
 * Tgc_StrongMail_HourlyInterval
 */
class Tgc_StrongMail_HourlyInterval {
}}

if (!class_exists("Tgc_StrongMail_DailyInterval", false)) {
/**
 * Tgc_StrongMail_DailyInterval
 */
class Tgc_StrongMail_DailyInterval {
}}

if (!class_exists("Tgc_StrongMail_WeeklyInterval", false)) {
/**
 * Tgc_StrongMail_WeeklyInterval
 */
class Tgc_StrongMail_WeeklyInterval {
}}

if (!class_exists("Tgc_StrongMail_MonthlyInterval", false)) {
/**
 * Tgc_StrongMail_MonthlyInterval
 */
class Tgc_StrongMail_MonthlyInterval {
}}

if (!class_exists("Tgc_StrongMail_DailyOccurrence", false)) {
/**
 * Tgc_StrongMail_DailyOccurrence
 */
class Tgc_StrongMail_DailyOccurrence {
}}

if (!class_exists("Tgc_StrongMail_WeeklyOccurrence", false)) {
/**
 * Tgc_StrongMail_WeeklyOccurrence
 */
class Tgc_StrongMail_WeeklyOccurrence {
}}

if (!class_exists("Tgc_StrongMail_MailingOrderBy", false)) {
/**
 * Tgc_StrongMail_MailingOrderBy
 */
class Tgc_StrongMail_MailingOrderBy {
}}

if (!class_exists("Tgc_StrongMail_AssetExpiryInterval", false)) {
/**
 * Tgc_StrongMail_AssetExpiryInterval
 */
class Tgc_StrongMail_AssetExpiryInterval {
}}

if (!class_exists("Tgc_StrongMail_CancelRequest", false)) {
/**
 * Tgc_StrongMail_CancelRequest
 */
class Tgc_StrongMail_CancelRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_MailingId
	 */
	public $mailingId;
}}

if (!class_exists("Tgc_StrongMail_CloseRequest", false)) {
/**
 * Tgc_StrongMail_CloseRequest
 */
class Tgc_StrongMail_CloseRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_TransactionalMailingId
	 */
	public $mailingId;
}}

if (!class_exists("Tgc_StrongMail_ArchiveRequest", false)) {
/**
 * Tgc_StrongMail_ArchiveRequest
 */
class Tgc_StrongMail_ArchiveRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_MailingId
	 */
	public $mailingId;
}}

if (!class_exists("Tgc_StrongMail_LaunchRequest", false)) {
/**
 * Tgc_StrongMail_LaunchRequest
 */
class Tgc_StrongMail_LaunchRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_StandardMailingId
	 */
	public $mailingId;
}}

if (!class_exists("Tgc_StrongMail_LoadRequest", false)) {
/**
 * Tgc_StrongMail_LoadRequest
 */
class Tgc_StrongMail_LoadRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_TransactionalMailingId
	 */
	public $mailingId;
}}

if (!class_exists("Tgc_StrongMail_PauseRequest", false)) {
/**
 * Tgc_StrongMail_PauseRequest
 */
class Tgc_StrongMail_PauseRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_MailingId
	 */
	public $mailingId;
}}

if (!class_exists("Tgc_StrongMail_ResumeRequest", false)) {
/**
 * Tgc_StrongMail_ResumeRequest
 */
class Tgc_StrongMail_ResumeRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_MailingId
	 */
	public $mailingId;
}}

if (!class_exists("Tgc_StrongMail_ScheduleRequest", false)) {
/**
 * Tgc_StrongMail_ScheduleRequest
 */
class Tgc_StrongMail_ScheduleRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_StandardMailingId
	 */
	public $mailingId;
}}

if (!class_exists("Tgc_StrongMail_SendRecord", false)) {
/**
 * Tgc_StrongMail_SendRecord
 */
class Tgc_StrongMail_SendRecord {
	/**
	 * @access public
	 * @var Tgc_StrongMail_NameValuePair[]
	 */
	public $field;
}}

if (!class_exists("Tgc_StrongMail_SendRequest", false)) {
/**
 * Tgc_StrongMail_SendRequest
 */
class Tgc_StrongMail_SendRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_TransactionalMailingId
	 */
	public $mailingId;
	/**
	 * @access public
	 * @var string
	 */
	public $sendData;
	/**
	 * @access public
	 * @var Tgc_StrongMail_SendRecord[]
	 */
	public $sendRecord;
}}

if (!class_exists("Tgc_StrongMail_GetTxnMailingHandleRequest", false)) {
/**
 * Tgc_StrongMail_GetTxnMailingHandleRequest
 */
class Tgc_StrongMail_GetTxnMailingHandleRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_TransactionalMailingId
	 */
	public $mailingId;
	/**
	 * @access public
	 * @var string
	 */
	public $mailingName;
}}

if (!class_exists("Tgc_StrongMail_TxnSendRequest", false)) {
/**
 * Tgc_StrongMail_TxnSendRequest
 */
class Tgc_StrongMail_TxnSendRequest {
	/**
	 * @access public
	 * @var string
	 */
	public $handle;
	/**
	 * @access public
	 * @var Tgc_StrongMail_SendRecord[]
	 */
	public $sendRecord;
}}

if (!class_exists("Tgc_StrongMail_GetTxnEasInfoRequest", false)) {
/**
 * Tgc_StrongMail_GetTxnEasInfoRequest
 */
class Tgc_StrongMail_GetTxnEasInfoRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_TransactionalMailingId
	 */
	public $mailingId;
}}

if (!class_exists("Tgc_StrongMail_GetAllEasByMailingIdRequest", false)) {
/**
 * Tgc_StrongMail_GetAllEasByMailingIdRequest
 */
class Tgc_StrongMail_GetAllEasByMailingIdRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_MailingId
	 */
	public $mailingId;
}}

if (!class_exists("Tgc_StrongMail_ProgramContactRecord", false)) {
/**
 * Tgc_StrongMail_ProgramContactRecord
 */
class Tgc_StrongMail_ProgramContactRecord {
	/**
	 * @access public
	 * @var string
	 */
	public $programDataSourcePk;
	/**
	 * @access public
	 * @var string
	 */
	public $contactId;
}}

if (!class_exists("Tgc_StrongMail_OrganizationOrderBy", false)) {
/**
 * Tgc_StrongMail_OrganizationOrderBy
 */
class Tgc_StrongMail_OrganizationOrderBy {
}}

if (!class_exists("Tgc_StrongMail_access", false)) {
/**
 * Tgc_StrongMail_access
 */
class Tgc_StrongMail_access {
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RoleId
	 */
	public $roleId;
}}

if (!class_exists("Tgc_StrongMail_UserOrderBy", false)) {
/**
 * Tgc_StrongMail_UserOrderBy
 */
class Tgc_StrongMail_UserOrderBy {
}}

if (!class_exists("Tgc_StrongMail_RolePermissions", false)) {
/**
 * Tgc_StrongMail_RolePermissions
 */
class Tgc_StrongMail_RolePermissions {
	/**
	 * @access public
	 * @var bool
	 */
	public $create;
	/**
	 * @access public
	 * @var bool
	 */
	public $update;
	/**
	 * @access public
	 * @var bool
	 */
	public $delete;
	/**
	 * @access public
	 * @var bool
	 */
	public $view;
	/**
	 * @access public
	 * @var bool
	 */
	public $approve;
	/**
	 * @access public
	 * @var bool
	 */
	public $advanced;
}}

if (!class_exists("Tgc_StrongMail_Permissions", false)) {
/**
 * Tgc_StrongMail_Permissions
 */
class Tgc_StrongMail_Permissions {
	/**
	 * @access public
	 * @var Tgc_StrongMail_RolePermissions
	 */
	public $internalDataSources;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RolePermissions
	 */
	public $externalDataSources;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RolePermissions
	 */
	public $targets;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RolePermissions
	 */
	public $suppressionLists;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RolePermissions
	 */
	public $seedLists;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RolePermissions
	 */
	public $messageTemplates;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RolePermissions
	 */
	public $attachments;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RolePermissions
	 */
	public $contentBlocks;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RolePermissions
	 */
	public $mailings;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RolePermissions
	 */
	public $txMailings;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RolePermissions
	 */
	public $reports;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RolePermissions
	 */
	public $bounceAddresses;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RolePermissions
	 */
	public $fromAddresses;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RolePermissions
	 */
	public $replyAddresses;
}}

if (!class_exists("Tgc_StrongMail_RoleOrderBy", false)) {
/**
 * Tgc_StrongMail_RoleOrderBy
 */
class Tgc_StrongMail_RoleOrderBy {
}}

if (!class_exists("Tgc_StrongMail_AssignedRoleOrderBy", false)) {
/**
 * Tgc_StrongMail_AssignedRoleOrderBy
 */
class Tgc_StrongMail_AssignedRoleOrderBy {
}}

if (!class_exists("Tgc_StrongMail_SystemAddressType", false)) {
/**
 * Tgc_StrongMail_SystemAddressType
 */
class Tgc_StrongMail_SystemAddressType {
}}

if (!class_exists("Tgc_StrongMail_DataSourceImportFrequency", false)) {
/**
 * Tgc_StrongMail_DataSourceImportFrequency
 */
class Tgc_StrongMail_DataSourceImportFrequency {
}}

if (!class_exists("Tgc_StrongMail_DataSourceImportMode", false)) {
/**
 * Tgc_StrongMail_DataSourceImportMode
 */
class Tgc_StrongMail_DataSourceImportMode {
}}

if (!class_exists("Tgc_StrongMail_SystemAddressOrderBy", false)) {
/**
 * Tgc_StrongMail_SystemAddressOrderBy
 */
class Tgc_StrongMail_SystemAddressOrderBy {
}}

if (!class_exists("Tgc_StrongMail_CampaignOrderBy", false)) {
/**
 * Tgc_StrongMail_CampaignOrderBy
 */
class Tgc_StrongMail_CampaignOrderBy {
}}

if (!class_exists("Tgc_StrongMail_server", false)) {
/**
 * Tgc_StrongMail_server
 */
class Tgc_StrongMail_server {
	/**
	 * @access public
	 * @var string
	 */
	public $defaultImagePath;
	/**
	 * @access public
	 * @var string
	 */
	public $host;
	/**
	 * @access public
	 * @var string
	 */
	public $login;
	/**
	 * @access public
	 * @var string
	 */
	public $password;
	/**
	 * @access public
	 * @var integer
	 */
	public $sshPort;
}}

if (!class_exists("Tgc_StrongMail_MediaServerOrderBy", false)) {
/**
 * Tgc_StrongMail_MediaServerOrderBy
 */
class Tgc_StrongMail_MediaServerOrderBy {
}}

if (!class_exists("Tgc_StrongMail_WebAnalyticsOrderBy", false)) {
/**
 * Tgc_StrongMail_WebAnalyticsOrderBy
 */
class Tgc_StrongMail_WebAnalyticsOrderBy {
}}

if (!class_exists("Tgc_StrongMail_MailingClassOrderBy", false)) {
/**
 * Tgc_StrongMail_MailingClassOrderBy
 */
class Tgc_StrongMail_MailingClassOrderBy {
}}

if (!class_exists("Tgc_StrongMail_Forward2FriendOfferTrackingOption", false)) {
/**
 * Tgc_StrongMail_Forward2FriendOfferTrackingOption
 */
class Tgc_StrongMail_Forward2FriendOfferTrackingOption {
}}

if (!class_exists("Tgc_StrongMail_StrongtoolOpenAs", false)) {
/**
 * Tgc_StrongMail_StrongtoolOpenAs
 */
class Tgc_StrongMail_StrongtoolOpenAs {
}}

if (!class_exists("Tgc_StrongMail_StrongtoolOrderBy", false)) {
/**
 * Tgc_StrongMail_StrongtoolOrderBy
 */
class Tgc_StrongMail_StrongtoolOrderBy {
}}

if (!class_exists("Tgc_StrongMail_OrganizationToken", false)) {
/**
 * Tgc_StrongMail_OrganizationToken
 */
class Tgc_StrongMail_OrganizationToken {
	/**
	 * @access public
	 * @var string
	 */
	public $organizationName;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $subOrganizationId;
}}

if (!class_exists("Tgc_StrongMail_IsSSO", false)) {
/**
 * Tgc_StrongMail_IsSSO
 */
class Tgc_StrongMail_IsSSO {
}}

if (!class_exists("Tgc_StrongMail_ObjectId", false)) {
/**
 * Tgc_StrongMail_ObjectId
 */
class Tgc_StrongMail_ObjectId {
	/**
	 * @access public
	 * @var string
	 */
	public $id;
}}

if (!class_exists("Tgc_StrongMail_BaseObject", false)) {
/**
 * Tgc_StrongMail_BaseObject
 */
class Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var dateTime
	 */
	public $modifiedTime;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ObjectId
	 */
	public $objectId;
	/**
	 * @access public
	 * @var integer
	 */
	public $version;
}}

if (!class_exists("Tgc_StrongMail_DedupeRecordsRequest", false)) {
/**
 * Tgc_StrongMail_DedupeRecordsRequest
 */
class Tgc_StrongMail_DedupeRecordsRequest {
	/**
	 * @access public
	 * @var string[]
	 */
	public $matchField;
}}

if (!class_exists("Tgc_StrongMail_DayOfWeek", false)) {
/**
 * Tgc_StrongMail_DayOfWeek
 */
class Tgc_StrongMail_DayOfWeek {
}}

if (!class_exists("Tgc_StrongMail_DayOfMonth", false)) {
/**
 * Tgc_StrongMail_DayOfMonth
 */
class Tgc_StrongMail_DayOfMonth {
}}

if (!class_exists("Tgc_StrongMail_Month", false)) {
/**
 * Tgc_StrongMail_Month
 */
class Tgc_StrongMail_Month {
}}

if (!class_exists("Tgc_StrongMail_NameValuePair", false)) {
/**
 * Tgc_StrongMail_NameValuePair
 */
class Tgc_StrongMail_NameValuePair {
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var string
	 */
	public $value;
}}

if (!class_exists("Tgc_StrongMail_Token", false)) {
/**
 * Tgc_StrongMail_Token
 */
class Tgc_StrongMail_Token {
}}

if (!class_exists("Tgc_StrongMail_BaseFilter", false)) {
/**
 * Tgc_StrongMail_BaseFilter
 */
class Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var bool
	 */
	public $isAscending;
	/**
	 * @access public
	 * @var integer
	 */
	public $pageNumber;
	/**
	 * @access public
	 * @var integer
	 */
	public $recordsPerPage;
	/**
	 * @access public
	 * @var integer
	 */
	public $maxRecordsPerPage;
}}

if (!class_exists("Tgc_StrongMail_FilterBooleanScalarOperator", false)) {
/**
 * Tgc_StrongMail_FilterBooleanScalarOperator
 */
class Tgc_StrongMail_FilterBooleanScalarOperator {
}}

if (!class_exists("Tgc_StrongMail_FilterIdScalarOperator", false)) {
/**
 * Tgc_StrongMail_FilterIdScalarOperator
 */
class Tgc_StrongMail_FilterIdScalarOperator {
}}

if (!class_exists("Tgc_StrongMail_FilterIntegerScalarOperator", false)) {
/**
 * Tgc_StrongMail_FilterIntegerScalarOperator
 */
class Tgc_StrongMail_FilterIntegerScalarOperator {
}}

if (!class_exists("Tgc_StrongMail_FilterStringScalarOperator", false)) {
/**
 * Tgc_StrongMail_FilterStringScalarOperator
 */
class Tgc_StrongMail_FilterStringScalarOperator {
}}

if (!class_exists("Tgc_StrongMail_FilterArrayOperator", false)) {
/**
 * Tgc_StrongMail_FilterArrayOperator
 */
class Tgc_StrongMail_FilterArrayOperator {
}}

if (!class_exists("Tgc_StrongMail_FilterCondition", false)) {
/**
 * Tgc_StrongMail_FilterCondition
 */
class Tgc_StrongMail_FilterCondition {
}}

if (!class_exists("Tgc_StrongMail_BooleanFilterCondition", false)) {
/**
 * Tgc_StrongMail_BooleanFilterCondition
 */
class Tgc_StrongMail_BooleanFilterCondition {
}}

if (!class_exists("Tgc_StrongMail_ScalarBooleanFilterCondition", false)) {
/**
 * Tgc_StrongMail_ScalarBooleanFilterCondition
 */
class Tgc_StrongMail_ScalarBooleanFilterCondition extends Tgc_StrongMail_BooleanFilterCondition {
	/**
	 * @access public
	 * @var bool
	 */
	public $value;
	/**
	 * @access public
	 * @var Tgc_StrongMail_FilterBooleanScalarOperator
	 */
	public $operator;
}}

if (!class_exists("Tgc_StrongMail_IntegerFilterCondition", false)) {
/**
 * Tgc_StrongMail_IntegerFilterCondition
 */
class Tgc_StrongMail_IntegerFilterCondition {
}}

if (!class_exists("Tgc_StrongMail_ScalarIntegerFilterCondition", false)) {
/**
 * Tgc_StrongMail_ScalarIntegerFilterCondition
 */
class Tgc_StrongMail_ScalarIntegerFilterCondition extends Tgc_StrongMail_IntegerFilterCondition {
	/**
	 * @access public
	 * @var integer
	 */
	public $value;
	/**
	 * @access public
	 * @var Tgc_StrongMail_FilterIntegerScalarOperator
	 */
	public $operator;
}}

if (!class_exists("Tgc_StrongMail_ArrayIntegerFilterCondition", false)) {
/**
 * Tgc_StrongMail_ArrayIntegerFilterCondition
 */
class Tgc_StrongMail_ArrayIntegerFilterCondition extends Tgc_StrongMail_IntegerFilterCondition {
	/**
	 * @access public
	 * @var integer[]
	 */
	public $value;
	/**
	 * @access public
	 * @var Tgc_StrongMail_FilterArrayOperator
	 */
	public $operator;
}}

if (!class_exists("Tgc_StrongMail_IdFilterCondition", false)) {
/**
 * Tgc_StrongMail_IdFilterCondition
 */
class Tgc_StrongMail_IdFilterCondition {
}}

if (!class_exists("Tgc_StrongMail_ScalarIdFilterCondition", false)) {
/**
 * Tgc_StrongMail_ScalarIdFilterCondition
 */
class Tgc_StrongMail_ScalarIdFilterCondition extends Tgc_StrongMail_IdFilterCondition {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ObjectId
	 */
	public $value;
	/**
	 * @access public
	 * @var Tgc_StrongMail_FilterIdScalarOperator
	 */
	public $operator;
}}

if (!class_exists("Tgc_StrongMail_ArrayIdFilterCondition", false)) {
/**
 * Tgc_StrongMail_ArrayIdFilterCondition
 */
class Tgc_StrongMail_ArrayIdFilterCondition extends Tgc_StrongMail_IdFilterCondition {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ObjectId[]
	 */
	public $value;
	/**
	 * @access public
	 * @var Tgc_StrongMail_FilterArrayOperator
	 */
	public $operator;
}}

if (!class_exists("Tgc_StrongMail_StringFilterCondition", false)) {
/**
 * Tgc_StrongMail_StringFilterCondition
 */
class Tgc_StrongMail_StringFilterCondition {
}}

if (!class_exists("Tgc_StrongMail_ScalarStringFilterCondition", false)) {
/**
 * Tgc_StrongMail_ScalarStringFilterCondition
 */
class Tgc_StrongMail_ScalarStringFilterCondition extends Tgc_StrongMail_StringFilterCondition {
	/**
	 * @access public
	 * @var string
	 */
	public $value;
	/**
	 * @access public
	 * @var Tgc_StrongMail_FilterStringScalarOperator
	 */
	public $operator;
}}

if (!class_exists("Tgc_StrongMail_ArrayStringFilterCondition", false)) {
/**
 * Tgc_StrongMail_ArrayStringFilterCondition
 */
class Tgc_StrongMail_ArrayStringFilterCondition extends Tgc_StrongMail_StringFilterCondition {
	/**
	 * @access public
	 * @var string[]
	 */
	public $value;
	/**
	 * @access public
	 * @var Tgc_StrongMail_FilterArrayOperator
	 */
	public $operator;
}}

if (!class_exists("Tgc_StrongMail_ComparisonOperation", false)) {
/**
 * Tgc_StrongMail_ComparisonOperation
 */
class Tgc_StrongMail_ComparisonOperation {
}}

if (!class_exists("Tgc_StrongMail_LogicalOperation", false)) {
/**
 * Tgc_StrongMail_LogicalOperation
 */
class Tgc_StrongMail_LogicalOperation {
}}

if (!class_exists("Tgc_StrongMail_AddRecordsRequest", false)) {
/**
 * Tgc_StrongMail_AddRecordsRequest
 */
class Tgc_StrongMail_AddRecordsRequest {
}}

if (!class_exists("Tgc_StrongMail_UpsertRecordsRequest", false)) {
/**
 * Tgc_StrongMail_UpsertRecordsRequest
 */
class Tgc_StrongMail_UpsertRecordsRequest {
}}

if (!class_exists("Tgc_StrongMail_GetRecordsRequest", false)) {
/**
 * Tgc_StrongMail_GetRecordsRequest
 */
class Tgc_StrongMail_GetRecordsRequest {
}}

if (!class_exists("Tgc_StrongMail_CopyRequest", false)) {
/**
 * Tgc_StrongMail_CopyRequest
 */
class Tgc_StrongMail_CopyRequest {
	/**
	 * @access public
	 * @var string
	 */
	public $newName;
}}

if (!class_exists("Tgc_StrongMail_CreateRequest", false)) {
/**
 * Tgc_StrongMail_CreateRequest
 */
class Tgc_StrongMail_CreateRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_BaseObject[]
	 */
	public $baseObject;
}}

if (!class_exists("Tgc_StrongMail_ExportRecordsRequest", false)) {
/**
 * Tgc_StrongMail_ExportRecordsRequest
 */
class Tgc_StrongMail_ExportRecordsRequest {
}}

if (!class_exists("Tgc_StrongMail_DeleteRequest", false)) {
/**
 * Tgc_StrongMail_DeleteRequest
 */
class Tgc_StrongMail_DeleteRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ObjectId[]
	 */
	public $objectId;
}}

if (!class_exists("Tgc_StrongMail_GetRequest", false)) {
/**
 * Tgc_StrongMail_GetRequest
 */
class Tgc_StrongMail_GetRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ObjectId[]
	 */
	public $objectId;
}}

if (!class_exists("Tgc_StrongMail_Response", false)) {
/**
 * Tgc_StrongMail_Response
 */
class Tgc_StrongMail_Response {
}}

if (!class_exists("Tgc_StrongMail_BatchResponse", false)) {
/**
 * Tgc_StrongMail_BatchResponse
 */
class Tgc_StrongMail_BatchResponse {
	/**
	 * @access public
	 * @var bool[]
	 */
	public $success;
	/**
	 * @access public
	 * @var Tgc_StrongMail_FaultDetail[]
	 */
	public $fault;
}}

if (!class_exists("Tgc_StrongMail_BatchUpdateResponse", false)) {
/**
 * Tgc_StrongMail_BatchUpdateResponse
 */
class Tgc_StrongMail_BatchUpdateResponse extends Tgc_StrongMail_BatchResponse {
	/**
	 * @access public
	 * @var Tgc_StrongMail_UpdateResponse[]
	 */
	public $updateResponse;
}}

if (!class_exists("Tgc_StrongMail_GetStatisticsRequest", false)) {
/**
 * Tgc_StrongMail_GetStatisticsRequest
 */
class Tgc_StrongMail_GetStatisticsRequest {
}}

if (!class_exists("Tgc_StrongMail_GetStatisticsResponse", false)) {
/**
 * Tgc_StrongMail_GetStatisticsResponse
 */
class Tgc_StrongMail_GetStatisticsResponse extends Tgc_StrongMail_Response {
}}

if (!class_exists("Tgc_StrongMail_ImportContentRequest", false)) {
/**
 * Tgc_StrongMail_ImportContentRequest
 */
class Tgc_StrongMail_ImportContentRequest {
	/**
	 * @access public
	 * @var base64Binary
	 */
	public $content;
}}

if (!class_exists("Tgc_StrongMail_ImportContentResponse", false)) {
/**
 * Tgc_StrongMail_ImportContentResponse
 */
class Tgc_StrongMail_ImportContentResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_ListRequest", false)) {
/**
 * Tgc_StrongMail_ListRequest
 */
class Tgc_StrongMail_ListRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_BaseFilter
	 */
	public $filter;
}}

if (!class_exists("Tgc_StrongMail_ListResponse", false)) {
/**
 * Tgc_StrongMail_ListResponse
 */
class Tgc_StrongMail_ListResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ObjectId[]
	 */
	public $objectId;
}}

if (!class_exists("Tgc_StrongMail_RemoveRecordsRequest", false)) {
/**
 * Tgc_StrongMail_RemoveRecordsRequest
 */
class Tgc_StrongMail_RemoveRecordsRequest {
}}

if (!class_exists("Tgc_StrongMail_RemoveRecordsResponse", false)) {
/**
 * Tgc_StrongMail_RemoveRecordsResponse
 */
class Tgc_StrongMail_RemoveRecordsResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var integer
	 */
	public $recordsRemoved;
}}

if (!class_exists("Tgc_StrongMail_TestRequest", false)) {
/**
 * Tgc_StrongMail_TestRequest
 */
class Tgc_StrongMail_TestRequest {
	/**
	 * @access public
	 * @var string[]
	 */
	public $address;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MessageFormat[]
	 */
	public $format;
	/**
	 * @access public
	 * @var string
	 */
	public $subjectPrefix;
	/**
	 * @access public
	 * @var Tgc_StrongMail_SeedListId
	 */
	public $testListId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_NameValuePair[]
	 */
	public $tokenValue;
	/**
	 * @access public
	 * @var bool
	 */
	public $useMultiPart;
}}

if (!class_exists("Tgc_StrongMail_TestResponse", false)) {
/**
 * Tgc_StrongMail_TestResponse
 */
class Tgc_StrongMail_TestResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_UpdateRequest", false)) {
/**
 * Tgc_StrongMail_UpdateRequest
 */
class Tgc_StrongMail_UpdateRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_BaseObject[]
	 */
	public $baseObject;
}}

if (!class_exists("Tgc_StrongMail_UpdateResponse", false)) {
/**
 * Tgc_StrongMail_UpdateResponse
 */
class Tgc_StrongMail_UpdateResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_CharSet", false)) {
/**
 * Tgc_StrongMail_CharSet
 */
class Tgc_StrongMail_CharSet {
}}

if (!class_exists("Tgc_StrongMail_Encoding", false)) {
/**
 * Tgc_StrongMail_Encoding
 */
class Tgc_StrongMail_Encoding {
}}

if (!class_exists("Tgc_StrongMail_FaultCode", false)) {
/**
 * Tgc_StrongMail_FaultCode
 */
class Tgc_StrongMail_FaultCode {
}}

if (!class_exists("Tgc_StrongMail_FaultMessage", false)) {
/**
 * Tgc_StrongMail_FaultMessage
 */
class Tgc_StrongMail_FaultMessage {
}}

if (!class_exists("Tgc_StrongMail_FaultDetail", false)) {
/**
 * Tgc_StrongMail_FaultDetail
 */
class Tgc_StrongMail_FaultDetail {
	/**
	 * @access public
	 * @var Tgc_StrongMail_FaultCode
	 */
	public $faultCode;
	/**
	 * @access public
	 * @var Tgc_StrongMail_FaultMessage
	 */
	public $faultMessage;
}}

if (!class_exists("Tgc_StrongMail_AuthorizationFaultDetail", false)) {
/**
 * Tgc_StrongMail_AuthorizationFaultDetail
 */
class Tgc_StrongMail_AuthorizationFaultDetail extends Tgc_StrongMail_FaultDetail {
}}

if (!class_exists("Tgc_StrongMail_ConcurrentModificationFaultDetail", false)) {
/**
 * Tgc_StrongMail_ConcurrentModificationFaultDetail
 */
class Tgc_StrongMail_ConcurrentModificationFaultDetail extends Tgc_StrongMail_FaultDetail {
}}

if (!class_exists("Tgc_StrongMail_InvalidObjectFaultDetail", false)) {
/**
 * Tgc_StrongMail_InvalidObjectFaultDetail
 */
class Tgc_StrongMail_InvalidObjectFaultDetail extends Tgc_StrongMail_FaultDetail {
}}

if (!class_exists("Tgc_StrongMail_ObjectNotFoundFaultDetail", false)) {
/**
 * Tgc_StrongMail_ObjectNotFoundFaultDetail
 */
class Tgc_StrongMail_ObjectNotFoundFaultDetail extends Tgc_StrongMail_FaultDetail {
}}

if (!class_exists("Tgc_StrongMail_StaleObjectFaultDetail", false)) {
/**
 * Tgc_StrongMail_StaleObjectFaultDetail
 */
class Tgc_StrongMail_StaleObjectFaultDetail extends Tgc_StrongMail_FaultDetail {
}}

if (!class_exists("Tgc_StrongMail_UnexpectedFaultDetail", false)) {
/**
 * Tgc_StrongMail_UnexpectedFaultDetail
 */
class Tgc_StrongMail_UnexpectedFaultDetail extends Tgc_StrongMail_FaultDetail {
}}

if (!class_exists("Tgc_StrongMail_UnrecognizedObjectTypeFaultDetail", false)) {
/**
 * Tgc_StrongMail_UnrecognizedObjectTypeFaultDetail
 */
class Tgc_StrongMail_UnrecognizedObjectTypeFaultDetail extends Tgc_StrongMail_FaultDetail {
}}

if (!class_exists("Tgc_StrongMail_BadHandleFaultDetail", false)) {
/**
 * Tgc_StrongMail_BadHandleFaultDetail
 */
class Tgc_StrongMail_BadHandleFaultDetail extends Tgc_StrongMail_FaultDetail {
}}

if (!class_exists("Tgc_StrongMail_GetSingleSignOnURLResponse", false)) {
/**
 * Tgc_StrongMail_GetSingleSignOnURLResponse
 */
class Tgc_StrongMail_GetSingleSignOnURLResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var string
	 */
	public $url;
}}

if (!class_exists("Tgc_StrongMail_DataSourceId", false)) {
/**
 * Tgc_StrongMail_DataSourceId
 */
class Tgc_StrongMail_DataSourceId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_InternalDataSourceId", false)) {
/**
 * Tgc_StrongMail_InternalDataSourceId
 */
class Tgc_StrongMail_InternalDataSourceId extends Tgc_StrongMail_DataSourceId {
}}

if (!class_exists("Tgc_StrongMail_ExternalDataSourceId", false)) {
/**
 * Tgc_StrongMail_ExternalDataSourceId
 */
class Tgc_StrongMail_ExternalDataSourceId extends Tgc_StrongMail_DataSourceId {
}}

if (!class_exists("Tgc_StrongMail_InternalDataSourceExtnId", false)) {
/**
 * Tgc_StrongMail_InternalDataSourceExtnId
 */
class Tgc_StrongMail_InternalDataSourceExtnId extends Tgc_StrongMail_DataSourceId {
}}

if (!class_exists("Tgc_StrongMail_DataSource", false)) {
/**
 * Tgc_StrongMail_DataSource
 */
class Tgc_StrongMail_DataSource extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var Tgc_StrongMail_CampaignId
	 */
	public $campaignId;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $createdTime;
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceField[]
	 */
	public $field;
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceOperationStatus
	 */
	public $operationStatus;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_UserId
	 */
	public $ownerId;
}}

if (!class_exists("Tgc_StrongMail_InternalDataSource", false)) {
/**
 * Tgc_StrongMail_InternalDataSource
 */
class Tgc_StrongMail_InternalDataSource extends Tgc_StrongMail_DataSource {
}}

if (!class_exists("Tgc_StrongMail_InternalDataSourceExtn", false)) {
/**
 * Tgc_StrongMail_InternalDataSourceExtn
 */
class Tgc_StrongMail_InternalDataSourceExtn extends Tgc_StrongMail_DataSource {
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceImportFrequency
	 */
	public $importFrequency;
	/**
	 * @access public
	 * @var string
	 */
	public $startTime;
	/**
	 * @access public
	 * @var integer
	 */
	public $hourlyInterval;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DayOfWeek[]
	 */
	public $weeklyDays;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceImportMode
	 */
	public $importMode;
	/**
	 * @access public
	 * @var bool
	 */
	public $useFTP;
}}

if (!class_exists("Tgc_StrongMail_ExternalDataSource", false)) {
/**
 * Tgc_StrongMail_ExternalDataSource
 */
class Tgc_StrongMail_ExternalDataSource extends Tgc_StrongMail_DataSource {
	/**
	 * @access public
	 * @var anyType
	 */
	public $connectionInfo;
	/**
	 * @access public
	 * @var string
	 */
	public $databaseName;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DatabaseType
	 */
	public $databaseType;
	/**
	 * @access public
	 * @var string
	 */
	public $hostname;
	/**
	 * @access public
	 * @var string
	 */
	public $password;
	/**
	 * @access public
	 * @var string
	 */
	public $port;
	/**
	 * @access public
	 * @var string
	 */
	public $username;
	/**
	 * @access public
	 * @var bool
	 */
	public $enableLocalCopy;
	/**
	 * @access public
	 * @var string
	 */
	public $tableName;
	/**
	 * @access public
	 * @var bool
	 */
	public $allowRefreshAtLaunchTime;
	/**
	 * @access public
	 * @var anyType
	 */
	public $hourlyRefresh;
	/**
	 * @access public
	 * @var Tgc_StrongMail_HourlyInterval
	 */
	public $interval;
	/**
	 * @access public
	 * @var anyType
	 */
	public $dailyRefresh;
	/**
	 * @access public
	 * @var time
	 */
	public $startTime;
	/**
	 * @access public
	 * @var anyType
	 */
	public $weeklyRefresh;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DayOfWeek[]
	 */
	public $day;
	/**
	 * @access public
	 * @var string
	 */
	public $writebackTable;
	/**
	 * @access public
	 * @var string
	 */
	public $advancedQuery;
	/**
	 * @access public
	 * @var string
	 */
	public $sourceTableName;
}}

if (!class_exists("Tgc_StrongMail_DataSourceFilter", false)) {
/**
 * Tgc_StrongMail_DataSourceFilter
 */
class Tgc_StrongMail_DataSourceFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $campaignIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $nameCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $typeCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $userIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_AddDataSourceRecordsRequest", false)) {
/**
 * Tgc_StrongMail_AddDataSourceRecordsRequest
 */
class Tgc_StrongMail_AddDataSourceRecordsRequest extends Tgc_StrongMail_AddRecordsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceId
	 */
	public $dataSourceId;
	/**
	 * @access public
	 * @var bool
	 */
	public $containsFieldNames;
	/**
	 * @access public
	 * @var string
	 */
	public $fieldDelimiter;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceRecord[]
	 */
	public $dataSourceRecord;
	/**
	 * @access public
	 * @var string
	 */
	public $ftpFileName;
	/**
	 * @access public
	 * @var base64Binary
	 */
	public $dataSourceRecordsAttachment;
}}

if (!class_exists("Tgc_StrongMail_UpsertDataSourceRecordsRequest", false)) {
/**
 * Tgc_StrongMail_UpsertDataSourceRecordsRequest
 */
class Tgc_StrongMail_UpsertDataSourceRecordsRequest extends Tgc_StrongMail_UpsertRecordsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceId
	 */
	public $dataSourceId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceRecord[]
	 */
	public $dataSourceRecord;
}}

if (!class_exists("Tgc_StrongMail_GetDataSourceRecordsRequest", false)) {
/**
 * Tgc_StrongMail_GetDataSourceRecordsRequest
 */
class Tgc_StrongMail_GetDataSourceRecordsRequest extends Tgc_StrongMail_GetRecordsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceId
	 */
	public $dataSourceId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_NameValuePair[]
	 */
	public $matchFields;
}}

if (!class_exists("Tgc_StrongMail_RemoveDataSourceRecordsRequest", false)) {
/**
 * Tgc_StrongMail_RemoveDataSourceRecordsRequest
 */
class Tgc_StrongMail_RemoveDataSourceRecordsRequest extends Tgc_StrongMail_RemoveRecordsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceId
	 */
	public $dataSourceId;
	/**
	 * @access public
	 * @var string
	 */
	public $matchFieldName;
	/**
	 * @access public
	 * @var string[]
	 */
	public $record;
	/**
	 * @access public
	 * @var base64Binary
	 */
	public $recordsAttachment;
}}

if (!class_exists("Tgc_StrongMail_ExportDataSourceRecordsRequest", false)) {
/**
 * Tgc_StrongMail_ExportDataSourceRecordsRequest
 */
class Tgc_StrongMail_ExportDataSourceRecordsRequest extends Tgc_StrongMail_ExportRecordsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceId
	 */
	public $dataSourceId;
	/**
	 * @access public
	 * @var bool
	 */
	public $useMalformedRecords;
	/**
	 * @access public
	 * @var string
	 */
	public $fieldDelimiter;
	/**
	 * @access public
	 * @var string
	 */
	public $rowDelimiter;
}}

if (!class_exists("Tgc_StrongMail_CopyDataSourceRequest", false)) {
/**
 * Tgc_StrongMail_CopyDataSourceRequest
 */
class Tgc_StrongMail_CopyDataSourceRequest extends Tgc_StrongMail_CopyRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_InternalDataSourceId
	 */
	public $fromId;
}}

if (!class_exists("Tgc_StrongMail_DedupeDataSourceRecordsRequest", false)) {
/**
 * Tgc_StrongMail_DedupeDataSourceRecordsRequest
 */
class Tgc_StrongMail_DedupeDataSourceRecordsRequest extends Tgc_StrongMail_DedupeRecordsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_InternalDataSourceId
	 */
	public $dataSourceId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceDedupeOption
	 */
	public $option;
}}

if (!class_exists("Tgc_StrongMail_DedupeRecordsResponse", false)) {
/**
 * Tgc_StrongMail_DedupeRecordsResponse
 */
class Tgc_StrongMail_DedupeRecordsResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_GetDataSourceStatisticsRequest", false)) {
/**
 * Tgc_StrongMail_GetDataSourceStatisticsRequest
 */
class Tgc_StrongMail_GetDataSourceStatisticsRequest extends Tgc_StrongMail_GetStatisticsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceId
	 */
	public $dataSourceId;
}}

if (!class_exists("Tgc_StrongMail_GetDataSourceStatisticsResponse", false)) {
/**
 * Tgc_StrongMail_GetDataSourceStatisticsResponse
 */
class Tgc_StrongMail_GetDataSourceStatisticsResponse extends Tgc_StrongMail_GetStatisticsResponse {
	/**
	 * @access public
	 * @var integer
	 */
	public $totalInvalidRecords;
	/**
	 * @access public
	 * @var integer
	 */
	public $totalMalformedRecords;
	/**
	 * @access public
	 * @var integer
	 */
	public $totalRecords;
	/**
	 * @access public
	 * @var integer
	 */
	public $totalUnsubscribedRecords;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $lastRefresh;
}}

if (!class_exists("Tgc_StrongMail_RefreshRecordsResponse", false)) {
/**
 * Tgc_StrongMail_RefreshRecordsResponse
 */
class Tgc_StrongMail_RefreshRecordsResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_CancelRefreshRecordsResponse", false)) {
/**
 * Tgc_StrongMail_CancelRefreshRecordsResponse
 */
class Tgc_StrongMail_CancelRefreshRecordsResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_TargetId", false)) {
/**
 * Tgc_StrongMail_TargetId
 */
class Tgc_StrongMail_TargetId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_Target", false)) {
/**
 * Tgc_StrongMail_Target
 */
class Tgc_StrongMail_Target extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var string
	 */
	public $advancedQuery;
	/**
	 * @access public
	 * @var string
	 */
	public $bounceFieldName;
	/**
	 * @access public
	 * @var Tgc_StrongMail_CampaignId
	 */
	public $campaignId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceId
	 */
	public $dataSourceId;
	/**
	 * @access public
	 * @var string
	 */
	public $emailAddressFieldName;
	/**
	 * @access public
	 * @var bool
	 */
	public $excludeBounce;
	/**
	 * @access public
	 * @var bool
	 */
	public $excludeUnsubscribe;
	/**
	 * @access public
	 * @var string
	 */
	public $smsAddressFieldName;
	/**
	 * @access public
	 * @var integer
	 */
	public $totalRecords;
	/**
	 * @access public
	 * @var Tgc_StrongMail_TargetType
	 */
	public $type;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceId
	 */
	public $retargetingDataSourceId;
	/**
	 * @access public
	 * @var string
	 */
	public $unsubscribeFieldName;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $createdTime;
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_UserId
	 */
	public $ownerId;
}}

if (!class_exists("Tgc_StrongMail_TargetFilter", false)) {
/**
 * Tgc_StrongMail_TargetFilter
 */
class Tgc_StrongMail_TargetFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $campaignIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $dataSourceIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $nameCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $typeCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $userIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_TargetOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_CopyTargetRequest", false)) {
/**
 * Tgc_StrongMail_CopyTargetRequest
 */
class Tgc_StrongMail_CopyTargetRequest extends Tgc_StrongMail_CopyRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_TargetId
	 */
	public $fromId;
}}

if (!class_exists("Tgc_StrongMail_ExportTargetRecordsRequest", false)) {
/**
 * Tgc_StrongMail_ExportTargetRecordsRequest
 */
class Tgc_StrongMail_ExportTargetRecordsRequest extends Tgc_StrongMail_ExportRecordsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_TargetId
	 */
	public $targetId;
	/**
	 * @access public
	 * @var string
	 */
	public $fieldDelimiter;
	/**
	 * @access public
	 * @var string
	 */
	public $rowDelimiter;
}}

if (!class_exists("Tgc_StrongMail_SuppressionListId", false)) {
/**
 * Tgc_StrongMail_SuppressionListId
 */
class Tgc_StrongMail_SuppressionListId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_SuppressionList", false)) {
/**
 * Tgc_StrongMail_SuppressionList
 */
class Tgc_StrongMail_SuppressionList extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var Tgc_StrongMail_CampaignId
	 */
	public $campaignId;
	/**
	 * @access public
	 * @var bool
	 */
	public $includeByDefaultOnMailings;
	/**
	 * @access public
	 * @var integer
	 */
	public $totalRecords;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $createdTime;
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_UserId
	 */
	public $ownerId;
}}

if (!class_exists("Tgc_StrongMail_SuppressionFilter", false)) {
/**
 * Tgc_StrongMail_SuppressionFilter
 */
class Tgc_StrongMail_SuppressionFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $campaignIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $nameCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $userIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_SuppressionListOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_AddSuppressionListRecordsRequest", false)) {
/**
 * Tgc_StrongMail_AddSuppressionListRecordsRequest
 */
class Tgc_StrongMail_AddSuppressionListRecordsRequest extends Tgc_StrongMail_AddRecordsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_SuppressionListId
	 */
	public $suppressionListId;
	/**
	 * @access public
	 * @var string[]
	 */
	public $addressMatch;
	/**
	 * @access public
	 * @var base64Binary
	 */
	public $addressMatchesAttachment;
}}

if (!class_exists("Tgc_StrongMail_CopySuppressionListRequest", false)) {
/**
 * Tgc_StrongMail_CopySuppressionListRequest
 */
class Tgc_StrongMail_CopySuppressionListRequest extends Tgc_StrongMail_CopyRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_SuppressionListId
	 */
	public $fromId;
}}

if (!class_exists("Tgc_StrongMail_ExportSuppressionListRecordsRequest", false)) {
/**
 * Tgc_StrongMail_ExportSuppressionListRecordsRequest
 */
class Tgc_StrongMail_ExportSuppressionListRecordsRequest extends Tgc_StrongMail_ExportRecordsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_SuppressionListId
	 */
	public $suppressionListId;
}}

if (!class_exists("Tgc_StrongMail_RemoveSuppressionListRecordsRequest", false)) {
/**
 * Tgc_StrongMail_RemoveSuppressionListRecordsRequest
 */
class Tgc_StrongMail_RemoveSuppressionListRecordsRequest extends Tgc_StrongMail_RemoveRecordsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_SuppressionListId
	 */
	public $suppressionListId;
	/**
	 * @access public
	 * @var string[]
	 */
	public $addressMatch;
	/**
	 * @access public
	 * @var base64Binary
	 */
	public $addressMatchesAttachment;
}}

if (!class_exists("Tgc_StrongMail_SeedListId", false)) {
/**
 * Tgc_StrongMail_SeedListId
 */
class Tgc_StrongMail_SeedListId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_SeedList", false)) {
/**
 * Tgc_StrongMail_SeedList
 */
class Tgc_StrongMail_SeedList extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var Tgc_StrongMail_CampaignId
	 */
	public $campaignId;
	/**
	 * @access public
	 * @var bool
	 */
	public $isTestList;
	/**
	 * @access public
	 * @var integer
	 */
	public $totalRecords;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $createdTime;
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_UserId
	 */
	public $ownerId;
}}

if (!class_exists("Tgc_StrongMail_SeedFilter", false)) {
/**
 * Tgc_StrongMail_SeedFilter
 */
class Tgc_StrongMail_SeedFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $campaignIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $nameCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $userIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_SeedListOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_AddSeedListRecordsRequest", false)) {
/**
 * Tgc_StrongMail_AddSeedListRecordsRequest
 */
class Tgc_StrongMail_AddSeedListRecordsRequest extends Tgc_StrongMail_AddRecordsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_SeedListId
	 */
	public $seedListId;
	/**
	 * @access public
	 * @var string[]
	 */
	public $address;
	/**
	 * @access public
	 * @var base64Binary
	 */
	public $addressesAttachment;
}}

if (!class_exists("Tgc_StrongMail_CopySeedListRequest", false)) {
/**
 * Tgc_StrongMail_CopySeedListRequest
 */
class Tgc_StrongMail_CopySeedListRequest extends Tgc_StrongMail_CopyRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_SeedListId
	 */
	public $fromId;
}}

if (!class_exists("Tgc_StrongMail_ExportSeedListRecordsRequest", false)) {
/**
 * Tgc_StrongMail_ExportSeedListRecordsRequest
 */
class Tgc_StrongMail_ExportSeedListRecordsRequest extends Tgc_StrongMail_ExportRecordsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_SeedListId
	 */
	public $seedListId;
}}

if (!class_exists("Tgc_StrongMail_RemoveSeedListRecordsRequest", false)) {
/**
 * Tgc_StrongMail_RemoveSeedListRecordsRequest
 */
class Tgc_StrongMail_RemoveSeedListRecordsRequest extends Tgc_StrongMail_RemoveRecordsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_SeedListId
	 */
	public $seedListId;
	/**
	 * @access public
	 * @var string[]
	 */
	public $address;
	/**
	 * @access public
	 * @var base64Binary
	 */
	public $addressesAttachment;
}}

if (!class_exists("Tgc_StrongMail_TemplateId", false)) {
/**
 * Tgc_StrongMail_TemplateId
 */
class Tgc_StrongMail_TemplateId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_Template", false)) {
/**
 * Tgc_StrongMail_Template
 */
class Tgc_StrongMail_Template extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var Tgc_StrongMail_AttachmentId[]
	 */
	public $attachmentId;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $createdTime;
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var Tgc_StrongMail_Encoding
	 */
	public $bodyEncoding;
	/**
	 * @access public
	 * @var Tgc_StrongMail_SystemAddressId
	 */
	public $bounceAddressId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_CampaignId
	 */
	public $campaignId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ContentBlockId[]
	 */
	public $contentBlockId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_SystemAddressId
	 */
	public $fromAddressId;
	/**
	 * @access public
	 * @var string
	 */
	public $fromName;
	/**
	 * @access public
	 * @var Tgc_StrongMail_Encoding
	 */
	public $headerEncoding;
	/**
	 * @access public
	 * @var string[]
	 */
	public $header;
	/**
	 * @access public
	 * @var bool
	 */
	public $isApproved;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MessagePart[]
	 */
	public $messagePart;
	/**
	 * @access public
	 * @var Tgc_StrongMail_AssetExpiryInterval
	 */
	public $assetExpiryInterval;
	/**
	 * @access public
	 * @var Tgc_StrongMail_CharSet
	 */
	public $outputBodyCharSet;
	/**
	 * @access public
	 * @var Tgc_StrongMail_Token
	 */
	public $outputBodyCharSetToken;
	/**
	 * @access public
	 * @var Tgc_StrongMail_CharSet
	 */
	public $outputHeaderCharSet;
	/**
	 * @access public
	 * @var Tgc_StrongMail_Token
	 */
	public $outputHeaderCharSetToken;
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_UserId
	 */
	public $ownerId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_SystemAddressId
	 */
	public $replyAddressId;
	/**
	 * @access public
	 * @var string
	 */
	public $subject;
	/**
	 * @access public
	 * @var string
	 */
	public $forward2FriendOffer;
	/**
	 * @access public
	 * @var Tgc_StrongMail_Forward2FriendOfferTrackingOption
	 */
	public $forward2FriendOfferTrackingOption;
}}

if (!class_exists("Tgc_StrongMail_TemplateFilter", false)) {
/**
 * Tgc_StrongMail_TemplateFilter
 */
class Tgc_StrongMail_TemplateFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarBooleanFilterCondition
	 */
	public $approvalCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $campaignIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $nameCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $userIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_TemplateOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_CopyTemplateRequest", false)) {
/**
 * Tgc_StrongMail_CopyTemplateRequest
 */
class Tgc_StrongMail_CopyTemplateRequest extends Tgc_StrongMail_CopyRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_TemplateId
	 */
	public $fromId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $newOrganizationId;
}}

if (!class_exists("Tgc_StrongMail_ImportMessagePartResponse", false)) {
/**
 * Tgc_StrongMail_ImportMessagePartResponse
 */
class Tgc_StrongMail_ImportMessagePartResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_TestTemplateRequest", false)) {
/**
 * Tgc_StrongMail_TestTemplateRequest
 */
class Tgc_StrongMail_TestTemplateRequest extends Tgc_StrongMail_TestRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_TemplateId
	 */
	public $templateId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MailingClassId
	 */
	public $mailingClassId;
}}

if (!class_exists("Tgc_StrongMail_ValidateXslResponse", false)) {
/**
 * Tgc_StrongMail_ValidateXslResponse
 */
class Tgc_StrongMail_ValidateXslResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $valid;
}}

if (!class_exists("Tgc_StrongMail_FetchLinkResponse", false)) {
/**
 * Tgc_StrongMail_FetchLinkResponse
 */
class Tgc_StrongMail_FetchLinkResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var Tgc_StrongMail_NamedLink
	 */
	public $namedLink;
}}

if (!class_exists("Tgc_StrongMail_FetchLinksResponse", false)) {
/**
 * Tgc_StrongMail_FetchLinksResponse
 */
class Tgc_StrongMail_FetchLinksResponse extends Tgc_StrongMail_BatchResponse {
	/**
	 * @access public
	 * @var Tgc_StrongMail_FetchLinkResponse[]
	 */
	public $fetchLinkResponse;
}}

if (!class_exists("Tgc_StrongMail_ContentBlockId", false)) {
/**
 * Tgc_StrongMail_ContentBlockId
 */
class Tgc_StrongMail_ContentBlockId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_ContentBlock", false)) {
/**
 * Tgc_StrongMail_ContentBlock
 */
class Tgc_StrongMail_ContentBlock extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var Tgc_StrongMail_CampaignId
	 */
	public $campaignId;
	/**
	 * @access public
	 * @var string
	 */
	public $content;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $createdTime;
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_UserId
	 */
	public $ownerId;
	/**
	 * @access public
	 * @var integer
	 */
	public $size;
	/**
	 * @access public
	 * @var Tgc_StrongMail_NamedLink[]
	 */
	public $namedLinks;
}}

if (!class_exists("Tgc_StrongMail_ContentBlockFilter", false)) {
/**
 * Tgc_StrongMail_ContentBlockFilter
 */
class Tgc_StrongMail_ContentBlockFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $campaignIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $nameCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $userIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ContentBlockOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_CopyContentBlockRequest", false)) {
/**
 * Tgc_StrongMail_CopyContentBlockRequest
 */
class Tgc_StrongMail_CopyContentBlockRequest extends Tgc_StrongMail_CopyRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ContentBlockId
	 */
	public $fromId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $newOrganizationId;
}}

if (!class_exists("Tgc_StrongMail_AttachmentId", false)) {
/**
 * Tgc_StrongMail_AttachmentId
 */
class Tgc_StrongMail_AttachmentId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_Attachment", false)) {
/**
 * Tgc_StrongMail_Attachment
 */
class Tgc_StrongMail_Attachment extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var Tgc_StrongMail_CampaignId
	 */
	public $campaignId;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $createdTime;
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var base64Binary
	 */
	public $content;
	/**
	 * @access public
	 * @var string
	 */
	public $fileName;
	/**
	 * @access public
	 * @var string
	 */
	public $fileReference;
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_UserId
	 */
	public $ownerId;
	/**
	 * @access public
	 * @var integer
	 */
	public $size;
}}

if (!class_exists("Tgc_StrongMail_AttachmentFilter", false)) {
/**
 * Tgc_StrongMail_AttachmentFilter
 */
class Tgc_StrongMail_AttachmentFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $campaignIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $nameCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $userIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_AttachmentOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_ImportAttachmentContentRequest", false)) {
/**
 * Tgc_StrongMail_ImportAttachmentContentRequest
 */
class Tgc_StrongMail_ImportAttachmentContentRequest extends Tgc_StrongMail_ImportContentRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_AttachmentId
	 */
	public $attachmentId;
	/**
	 * @access public
	 * @var string
	 */
	public $fileName;
}}

if (!class_exists("Tgc_StrongMail_RuleId", false)) {
/**
 * Tgc_StrongMail_RuleId
 */
class Tgc_StrongMail_RuleId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_Rule", false)) {
/**
 * Tgc_StrongMail_Rule
 */
class Tgc_StrongMail_Rule extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var Tgc_StrongMail_CampaignId
	 */
	public $campaignId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_TargetId[]
	 */
	public $targetId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RuleIfPart
	 */
	public $ifPart;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RuleThenPart
	 */
	public $thenPart;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RuleElsePart
	 */
	public $elsePart;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $createdTime;
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_UserId
	 */
	public $ownerId;
}}

if (!class_exists("Tgc_StrongMail_RuleFilter", false)) {
/**
 * Tgc_StrongMail_RuleFilter
 */
class Tgc_StrongMail_RuleFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $campaignIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $nameCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RuleOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_CopyRuleRequest", false)) {
/**
 * Tgc_StrongMail_CopyRuleRequest
 */
class Tgc_StrongMail_CopyRuleRequest extends Tgc_StrongMail_CopyRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_RuleId
	 */
	public $fromId;
}}

if (!class_exists("Tgc_StrongMail_MailingId", false)) {
/**
 * Tgc_StrongMail_MailingId
 */
class Tgc_StrongMail_MailingId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_StandardMailingId", false)) {
/**
 * Tgc_StrongMail_StandardMailingId
 */
class Tgc_StrongMail_StandardMailingId extends Tgc_StrongMail_MailingId {
}}

if (!class_exists("Tgc_StrongMail_TransactionalMailingId", false)) {
/**
 * Tgc_StrongMail_TransactionalMailingId
 */
class Tgc_StrongMail_TransactionalMailingId extends Tgc_StrongMail_MailingId {
}}

if (!class_exists("Tgc_StrongMail_Mailing", false)) {
/**
 * Tgc_StrongMail_Mailing
 */
class Tgc_StrongMail_Mailing extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var Tgc_StrongMail_AttachmentId[]
	 */
	public $attachmentId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_Encoding
	 */
	public $bodyEncoding;
	/**
	 * @access public
	 * @var Tgc_StrongMail_SystemAddressId
	 */
	public $bounceAddressId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_CampaignId
	 */
	public $campaignId;
	/**
	 * @access public
	 * @var bool
	 */
	public $isApproved;
	/**
	 * @access public
	 * @var bool
	 */
	public $isCompliant;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ContentBlockId[]
	 */
	public $contentBlockId;
	/**
	 * @access public
	 * @var string
	 */
	public $fieldDelimiter;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MessageFormat[]
	 */
	public $format;
	/**
	 * @access public
	 * @var Tgc_StrongMail_SystemAddressId
	 */
	public $fromAddressId;
	/**
	 * @access public
	 * @var string
	 */
	public $fromName;
	/**
	 * @access public
	 * @var Tgc_StrongMail_Encoding
	 */
	public $headerEncoding;
	/**
	 * @access public
	 * @var string[]
	 */
	public $header;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MailingStatus
	 */
	public $lastGoodStatus;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MailingClassId
	 */
	public $mailingClassId;
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MailingPriority
	 */
	public $priority;
	/**
	 * @access public
	 * @var Tgc_StrongMail_CharSet
	 */
	public $outputBodyCharSet;
	/**
	 * @access public
	 * @var Tgc_StrongMail_Token
	 */
	public $outputBodyCharSetToken;
	/**
	 * @access public
	 * @var Tgc_StrongMail_CharSet
	 */
	public $outputHeaderCharSet;
	/**
	 * @access public
	 * @var Tgc_StrongMail_Token
	 */
	public $outputHeaderCharSetToken;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MailingId
	 */
	public $parentId;
	/**
	 * @access public
	 * @var date
	 */
	public $plannedLaunchDate;
	/**
	 * @access public
	 * @var Tgc_StrongMail_SystemAddressId
	 */
	public $replyAddressId;
	/**
	 * @access public
	 * @var string
	 */
	public $rowDelimiter;
	/**
	 * @access public
	 * @var integer
	 */
	public $serverErrorCode;
	/**
	 * @access public
	 * @var string
	 */
	public $serverErrorMessage;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MailingStatus
	 */
	public $status;
	/**
	 * @access public
	 * @var string
	 */
	public $subject;
	/**
	 * @access public
	 * @var Tgc_StrongMail_TemplateId
	 */
	public $templateId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MailingType
	 */
	public $type;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $createdTime;
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_UserId
	 */
	public $ownerId;
	/**
	 * @access public
	 * @var string
	 */
	public $forward2FriendOffer;
	/**
	 * @access public
	 * @var Tgc_StrongMail_Forward2FriendOfferTrackingOption
	 */
	public $forward2FriendOfferTrackingOption;
}}

if (!class_exists("Tgc_StrongMail_SchedulableMailing", false)) {
/**
 * Tgc_StrongMail_SchedulableMailing
 */
class Tgc_StrongMail_SchedulableMailing extends Tgc_StrongMail_Mailing {
	/**
	 * @access public
	 * @var anyType
	 */
	public $schedule;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $startDateTime;
	/**
	 * @access public
	 * @var anyType
	 */
	public $recurrence;
	/**
	 * @access public
	 * @var date
	 */
	public $endDate;
	/**
	 * @access public
	 * @var integer
	 */
	public $endAfterXMailings;
	/**
	 * @access public
	 * @var anyType
	 */
	public $minutelyRecurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MinutelyInterval
	 */
	public $interval;
	/**
	 * @access public
	 * @var anyType
	 */
	public $hourlyRecurrence;
	/**
	 * @access public
	 * @var anyType
	 */
	public $dailyRecurrence;
	/**
	 * @access public
	 * @var bool
	 */
	public $everyWeekDay;
	/**
	 * @access public
	 * @var anyType
	 */
	public $weeklyRecurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DayOfWeek[]
	 */
	public $day;
	/**
	 * @access public
	 * @var anyType
	 */
	public $monthlyByDateRecurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DayOfMonth[]
	 */
	public $dayOfMonth;
	/**
	 * @access public
	 * @var anyType
	 */
	public $monthlyByDayRecurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_WeeklyOccurrence
	 */
	public $weeklyOccurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_DailyOccurrence
	 */
	public $dailyOccurrence;
	/**
	 * @access public
	 * @var anyType
	 */
	public $yearlyByDateRecurrence;
	/**
	 * @access public
	 * @var Tgc_StrongMail_Month
	 */
	public $month;
	/**
	 * @access public
	 * @var anyType
	 */
	public $yearlyByDayRecurrence;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $nextScheduledDateTime;
}}

if (!class_exists("Tgc_StrongMail_StandardMailing", false)) {
/**
 * Tgc_StrongMail_StandardMailing
 */
class Tgc_StrongMail_StandardMailing extends Tgc_StrongMail_SchedulableMailing {
	/**
	 * @access public
	 * @var bool
	 */
	public $eliminateDuplicates;
	/**
	 * @access public
	 * @var Tgc_StrongMail_TargetId[]
	 */
	public $excludedTargetId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_TargetId[]
	 */
	public $includedTargetId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_SeedListId[]
	 */
	public $seedListId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_SuppressionListId[]
	 */
	public $suppressionListId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_AssetExpiryInterval
	 */
	public $assetExpiryInterval;
}}

if (!class_exists("Tgc_StrongMail_TransactionalMailing", false)) {
/**
 * Tgc_StrongMail_TransactionalMailing
 */
class Tgc_StrongMail_TransactionalMailing extends Tgc_StrongMail_Mailing {
	/**
	 * @access public
	 * @var string
	 */
	public $formatFieldName;
	/**
	 * @access public
	 * @var string
	 */
	public $mailingConfig;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MessageType
	 */
	public $messageType;
	/**
	 * @access public
	 * @var string[]
	 */
	public $recordStructure;
	/**
	 * @access public
	 * @var string
	 */
	public $senderNumber;
	/**
	 * @access public
	 * @var Tgc_StrongMail_TargetId
	 */
	public $targetId;
}}

if (!class_exists("Tgc_StrongMail_MailingFilter", false)) {
/**
 * Tgc_StrongMail_MailingFilter
 */
class Tgc_StrongMail_MailingFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $campaignIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $nameCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $userIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ArrayStringFilterCondition
	 */
	public $typeCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ArrayStringFilterCondition
	 */
	public $statusCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MailingOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_CopyMailingRequest", false)) {
/**
 * Tgc_StrongMail_CopyMailingRequest
 */
class Tgc_StrongMail_CopyMailingRequest extends Tgc_StrongMail_CopyRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_MailingId
	 */
	public $fromId;
}}

if (!class_exists("Tgc_StrongMail_CancelResponse", false)) {
/**
 * Tgc_StrongMail_CancelResponse
 */
class Tgc_StrongMail_CancelResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_CloseResponse", false)) {
/**
 * Tgc_StrongMail_CloseResponse
 */
class Tgc_StrongMail_CloseResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_ArchiveResponse", false)) {
/**
 * Tgc_StrongMail_ArchiveResponse
 */
class Tgc_StrongMail_ArchiveResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_GetMailingStatisticsRequest", false)) {
/**
 * Tgc_StrongMail_GetMailingStatisticsRequest
 */
class Tgc_StrongMail_GetMailingStatisticsRequest extends Tgc_StrongMail_GetStatisticsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_MailingId
	 */
	public $mailingId;
}}

if (!class_exists("Tgc_StrongMail_GetMailingStatisticsResponse", false)) {
/**
 * Tgc_StrongMail_GetMailingStatisticsResponse
 */
class Tgc_StrongMail_GetMailingStatisticsResponse extends Tgc_StrongMail_GetStatisticsResponse {
	/**
	 * @access public
	 * @var string
	 */
	public $launchSerial;
	/**
	 * @access public
	 * @var duration
	 */
	public $elapsedTime;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $launchTime;
	/**
	 * @access public
	 * @var dateTime
	 */
	public $completionTime;
	/**
	 * @access public
	 * @var integer
	 */
	public $deferred;
	/**
	 * @access public
	 * @var integer
	 */
	public $delivered;
	/**
	 * @access public
	 * @var integer
	 */
	public $failed;
	/**
	 * @access public
	 * @var integer
	 */
	public $invalid;
	/**
	 * @access public
	 * @var integer
	 */
	public $sent;
	/**
	 * @access public
	 * @var integer
	 */
	public $targeted;
	/**
	 * @access public
	 * @var integer
	 */
	public $totalClicks;
	/**
	 * @access public
	 * @var integer
	 */
	public $totalComplaints;
	/**
	 * @access public
	 * @var integer
	 */
	public $totalOpens;
	/**
	 * @access public
	 * @var integer
	 */
	public $totalUnsubscribes;
	/**
	 * @access public
	 * @var integer
	 */
	public $uniqueClicks;
	/**
	 * @access public
	 * @var integer
	 */
	public $uniqueComplaints;
	/**
	 * @access public
	 * @var integer
	 */
	public $uniqueOpens;
	/**
	 * @access public
	 * @var integer
	 */
	public $uniqueUnsubscribes;
}}

if (!class_exists("Tgc_StrongMail_LaunchResponse", false)) {
/**
 * Tgc_StrongMail_LaunchResponse
 */
class Tgc_StrongMail_LaunchResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_LoadResponse", false)) {
/**
 * Tgc_StrongMail_LoadResponse
 */
class Tgc_StrongMail_LoadResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_PauseResponse", false)) {
/**
 * Tgc_StrongMail_PauseResponse
 */
class Tgc_StrongMail_PauseResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_ResumeResponse", false)) {
/**
 * Tgc_StrongMail_ResumeResponse
 */
class Tgc_StrongMail_ResumeResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_ScheduleResponse", false)) {
/**
 * Tgc_StrongMail_ScheduleResponse
 */
class Tgc_StrongMail_ScheduleResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_SendResponse", false)) {
/**
 * Tgc_StrongMail_SendResponse
 */
class Tgc_StrongMail_SendResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_GetTxnMailingHandleResponse", false)) {
/**
 * Tgc_StrongMail_GetTxnMailingHandleResponse
 */
class Tgc_StrongMail_GetTxnMailingHandleResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var string
	 */
	public $handle;
}}

if (!class_exists("Tgc_StrongMail_TxnSendResponse", false)) {
/**
 * Tgc_StrongMail_TxnSendResponse
 */
class Tgc_StrongMail_TxnSendResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_GetTxnEasInfoResponse", false)) {
/**
 * Tgc_StrongMail_GetTxnEasInfoResponse
 */
class Tgc_StrongMail_GetTxnEasInfoResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var string
	 */
	public $hostname;
	/**
	 * @access public
	 * @var string
	 */
	public $mailingName;
	/**
	 * @access public
	 * @var string
	 */
	public $mailingHandle;
}}

if (!class_exists("Tgc_StrongMail_GetAllEasListByMailingIdResponse", false)) {
/**
 * Tgc_StrongMail_GetAllEasListByMailingIdResponse
 */
class Tgc_StrongMail_GetAllEasListByMailingIdResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var Tgc_StrongMail_GetAllEasByMailingIdResponse[]
	 */
	public $GetAllEasByMailingIdResponse;
}}

if (!class_exists("Tgc_StrongMail_GetAllEasByMailingIdResponse", false)) {
/**
 * Tgc_StrongMail_GetAllEasByMailingIdResponse
 */
class Tgc_StrongMail_GetAllEasByMailingIdResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var string
	 */
	public $hostip;
	/**
	 * @access public
	 * @var string
	 */
	public $mailingConfigName;
	/**
	 * @access public
	 * @var string
	 */
	public $serialNumber;
	/**
	 * @access public
	 * @var string
	 */
	public $easId;
}}

if (!class_exists("Tgc_StrongMail_TestMailingRequest", false)) {
/**
 * Tgc_StrongMail_TestMailingRequest
 */
class Tgc_StrongMail_TestMailingRequest extends Tgc_StrongMail_TestRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_StandardMailingId
	 */
	public $mailingId;
}}

if (!class_exists("Tgc_StrongMail_ProgramId", false)) {
/**
 * Tgc_StrongMail_ProgramId
 */
class Tgc_StrongMail_ProgramId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_AddProgramContactRecordsRequest", false)) {
/**
 * Tgc_StrongMail_AddProgramContactRecordsRequest
 */
class Tgc_StrongMail_AddProgramContactRecordsRequest extends Tgc_StrongMail_AddRecordsRequest {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ProgramId
	 */
	public $programId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ProgramContactRecord[]
	 */
	public $programContactRecord;
}}

if (!class_exists("Tgc_StrongMail_OrganizationId", false)) {
/**
 * Tgc_StrongMail_OrganizationId
 */
class Tgc_StrongMail_OrganizationId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_Organization", false)) {
/**
 * Tgc_StrongMail_Organization
 */
class Tgc_StrongMail_Organization extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var base64Binary
	 */
	public $logo;
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $parentId;
	/**
	 * @access public
	 * @var string
	 */
	public $viewInBrowserExceptionURL;
	/**
	 * @access public
	 * @var string
	 */
	public $forward2FriendExceptionURL;
	/**
	 * @access public
	 * @var string
	 */
	public $socialNotesExceptionURL;
	/**
	 * @access public
	 * @var string
	 */
	public $socialNotesWidget;
	/**
	 * @access public
	 * @var string
	 */
	public $forward2FriendOffer;
	/**
	 * @access public
	 * @var Tgc_StrongMail_Forward2FriendOfferTrackingOption
	 */
	public $forward2FriendOfferTrackingOption;
	/**
	 * @access public
	 * @var string
	 */
	public $influencerSecretKey;
	/**
	 * @access public
	 * @var string
	 */
	public $influencerClientUuid;
}}

if (!class_exists("Tgc_StrongMail_OrganizationFilter", false)) {
/**
 * Tgc_StrongMail_OrganizationFilter
 */
class Tgc_StrongMail_OrganizationFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $nameCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $parentIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_UserId", false)) {
/**
 * Tgc_StrongMail_UserId
 */
class Tgc_StrongMail_UserId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_User", false)) {
/**
 * Tgc_StrongMail_User
 */
class Tgc_StrongMail_User extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var anyType[]
	 */
	public $access;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_RoleId
	 */
	public $roleId;
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var string
	 */
	public $email;
	/**
	 * @access public
	 * @var string
	 */
	public $firstName;
	/**
	 * @access public
	 * @var bool
	 */
	public $isAdmin;
	/**
	 * @access public
	 * @var bool
	 */
	public $isSuperUser;
	/**
	 * @access public
	 * @var string
	 */
	public $lastName;
	/**
	 * @access public
	 * @var string
	 */
	public $login;
	/**
	 * @access public
	 * @var string
	 */
	public $password;
}}

if (!class_exists("Tgc_StrongMail_UserFilter", false)) {
/**
 * Tgc_StrongMail_UserFilter
 */
class Tgc_StrongMail_UserFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarBooleanFilterCondition
	 */
	public $isAdminCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $loginNameCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_UserOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_RoleId", false)) {
/**
 * Tgc_StrongMail_RoleId
 */
class Tgc_StrongMail_RoleId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_Role", false)) {
/**
 * Tgc_StrongMail_Role
 */
class Tgc_StrongMail_Role extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_Permissions
	 */
	public $permissions;
}}

if (!class_exists("Tgc_StrongMail_RoleFilter", false)) {
/**
 * Tgc_StrongMail_RoleFilter
 */
class Tgc_StrongMail_RoleFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_RoleOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_AssignedRoleId", false)) {
/**
 * Tgc_StrongMail_AssignedRoleId
 */
class Tgc_StrongMail_AssignedRoleId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_AssignedRole", false)) {
/**
 * Tgc_StrongMail_AssignedRole
 */
class Tgc_StrongMail_AssignedRole extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var Tgc_StrongMail_RoleId
	 */
	public $roleId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_UserId
	 */
	public $userId;
}}

if (!class_exists("Tgc_StrongMail_AssignedRoleFilter", false)) {
/**
 * Tgc_StrongMail_AssignedRoleFilter
 */
class Tgc_StrongMail_AssignedRoleFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $roleIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $organizationIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $userIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_AssignedRoleOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_SystemAddressId", false)) {
/**
 * Tgc_StrongMail_SystemAddressId
 */
class Tgc_StrongMail_SystemAddressId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_SystemAddress", false)) {
/**
 * Tgc_StrongMail_SystemAddress
 */
class Tgc_StrongMail_SystemAddress extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var dateTime
	 */
	public $createdTime;
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var string
	 */
	public $emailAddress;
	/**
	 * @access public
	 * @var bool
	 */
	public $isBounce;
	/**
	 * @access public
	 * @var string
	 */
	public $fromName;
	/**
	 * @access public
	 * @var bool
	 */
	public $isFrom;
	/**
	 * @access public
	 * @var bool
	 */
	public $isReply;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_UserId
	 */
	public $ownerId;
}}

if (!class_exists("Tgc_StrongMail_SystemAddressFilter", false)) {
/**
 * Tgc_StrongMail_SystemAddressFilter
 */
class Tgc_StrongMail_SystemAddressFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $typeCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $userIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_SystemAddressOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_CampaignId", false)) {
/**
 * Tgc_StrongMail_CampaignId
 */
class Tgc_StrongMail_CampaignId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_Campaign", false)) {
/**
 * Tgc_StrongMail_Campaign
 */
class Tgc_StrongMail_Campaign extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var dateTime
	 */
	public $createdTime;
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_UserId
	 */
	public $ownerId;
}}

if (!class_exists("Tgc_StrongMail_CampaignFilter", false)) {
/**
 * Tgc_StrongMail_CampaignFilter
 */
class Tgc_StrongMail_CampaignFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $nameCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $userIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_CampaignOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_MediaServerId", false)) {
/**
 * Tgc_StrongMail_MediaServerId
 */
class Tgc_StrongMail_MediaServerId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_MediaServer", false)) {
/**
 * Tgc_StrongMail_MediaServer
 */
class Tgc_StrongMail_MediaServer extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var dateTime
	 */
	public $createdTime;
	/**
	 * @access public
	 * @var Tgc_StrongMail_anyURI
	 */
	public $defaultUrl;
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var bool
	 */
	public $isWritable;
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_UserId
	 */
	public $ownerId;
	/**
	 * @access public
	 * @var anyType[]
	 */
	public $server;
	/**
	 * @access public
	 * @var string
	 */
	public $defaultImagePath;
	/**
	 * @access public
	 * @var string
	 */
	public $host;
	/**
	 * @access public
	 * @var string
	 */
	public $login;
	/**
	 * @access public
	 * @var string
	 */
	public $password;
	/**
	 * @access public
	 * @var integer
	 */
	public $sshPort;
}}

if (!class_exists("Tgc_StrongMail_MediaServerFilter", false)) {
/**
 * Tgc_StrongMail_MediaServerFilter
 */
class Tgc_StrongMail_MediaServerFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $nameCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $urlCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $userIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarBooleanFilterCondition
	 */
	public $writableCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MediaServerOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_WebAnalyticsId", false)) {
/**
 * Tgc_StrongMail_WebAnalyticsId
 */
class Tgc_StrongMail_WebAnalyticsId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_WebAnalytics", false)) {
/**
 * Tgc_StrongMail_WebAnalytics
 */
class Tgc_StrongMail_WebAnalytics extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var dateTime
	 */
	public $createdTime;
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_UserId
	 */
	public $ownerId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_NameValuePair[]
	 */
	public $parameter;
}}

if (!class_exists("Tgc_StrongMail_WebAnalyticsFilter", false)) {
/**
 * Tgc_StrongMail_WebAnalyticsFilter
 */
class Tgc_StrongMail_WebAnalyticsFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $nameCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $userIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_WebAnalyticsOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_MailingClassId", false)) {
/**
 * Tgc_StrongMail_MailingClassId
 */
class Tgc_StrongMail_MailingClassId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_MailingClass", false)) {
/**
 * Tgc_StrongMail_MailingClass
 */
class Tgc_StrongMail_MailingClass extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var dateTime
	 */
	public $createdTime;
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId[]
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_UserId
	 */
	public $ownerId;
}}

if (!class_exists("Tgc_StrongMail_MailingClassFilter", false)) {
/**
 * Tgc_StrongMail_MailingClassFilter
 */
class Tgc_StrongMail_MailingClassFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $nameCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarIdFilterCondition
	 */
	public $userIdCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_MailingClassOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_StrongtoolId", false)) {
/**
 * Tgc_StrongMail_StrongtoolId
 */
class Tgc_StrongMail_StrongtoolId extends Tgc_StrongMail_ObjectId {
}}

if (!class_exists("Tgc_StrongMail_Strongtool", false)) {
/**
 * Tgc_StrongMail_Strongtool
 */
class Tgc_StrongMail_Strongtool extends Tgc_StrongMail_BaseObject {
	/**
	 * @access public
	 * @var string
	 */
	public $name;
	/**
	 * @access public
	 * @var string
	 */
	public $description;
	/**
	 * @access public
	 * @var string
	 */
	public $url;
	/**
	 * @access public
	 * @var Tgc_StrongMail_StrongtoolOpenAs
	 */
	public $openAs;
	/**
	 * @access public
	 * @var Tgc_StrongMail_OrganizationId
	 */
	public $organizationId;
	/**
	 * @access public
	 * @var Tgc_StrongMail_UserId
	 */
	public $ownerId;
}}

if (!class_exists("Tgc_StrongMail_StrongtoolFilter", false)) {
/**
 * Tgc_StrongMail_StrongtoolFilter
 */
class Tgc_StrongMail_StrongtoolFilter extends Tgc_StrongMail_BaseFilter {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $nameCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ScalarStringFilterCondition
	 */
	public $openAsCondition;
	/**
	 * @access public
	 * @var Tgc_StrongMail_StrongtoolOrderBy[]
	 */
	public $orderBy;
}}

if (!class_exists("Tgc_StrongMail_AddRecordsResponse", false)) {
/**
 * Tgc_StrongMail_AddRecordsResponse
 */
class Tgc_StrongMail_AddRecordsResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_UpsertRecordsResponse", false)) {
/**
 * Tgc_StrongMail_UpsertRecordsResponse
 */
class Tgc_StrongMail_UpsertRecordsResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_GetRecordsResponse", false)) {
/**
 * Tgc_StrongMail_GetRecordsResponse
 */
class Tgc_StrongMail_GetRecordsResponse extends Tgc_StrongMail_Response {
}}

if (!class_exists("Tgc_StrongMail_CopyResponse", false)) {
/**
 * Tgc_StrongMail_CopyResponse
 */
class Tgc_StrongMail_CopyResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ObjectId
	 */
	public $objectId;
}}

if (!class_exists("Tgc_StrongMail_BatchCreateResponse", false)) {
/**
 * Tgc_StrongMail_BatchCreateResponse
 */
class Tgc_StrongMail_BatchCreateResponse extends Tgc_StrongMail_BatchResponse {
	/**
	 * @access public
	 * @var Tgc_StrongMail_CreateResponse[]
	 */
	public $createResponse;
}}

if (!class_exists("Tgc_StrongMail_CreateResponse", false)) {
/**
 * Tgc_StrongMail_CreateResponse
 */
class Tgc_StrongMail_CreateResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var Tgc_StrongMail_ObjectId
	 */
	public $objectId;
}}

if (!class_exists("Tgc_StrongMail_ExportRecordsResponse", false)) {
/**
 * Tgc_StrongMail_ExportRecordsResponse
 */
class Tgc_StrongMail_ExportRecordsResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var base64Binary
	 */
	public $data;
}}

if (!class_exists("Tgc_StrongMail_BatchDeleteResponse", false)) {
/**
 * Tgc_StrongMail_BatchDeleteResponse
 */
class Tgc_StrongMail_BatchDeleteResponse extends Tgc_StrongMail_BatchResponse {
	/**
	 * @access public
	 * @var Tgc_StrongMail_DeleteResponse[]
	 */
	public $deleteResponse;
}}

if (!class_exists("Tgc_StrongMail_DeleteResponse", false)) {
/**
 * Tgc_StrongMail_DeleteResponse
 */
class Tgc_StrongMail_DeleteResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var bool
	 */
	public $success;
}}

if (!class_exists("Tgc_StrongMail_BatchGetResponse", false)) {
/**
 * Tgc_StrongMail_BatchGetResponse
 */
class Tgc_StrongMail_BatchGetResponse extends Tgc_StrongMail_BatchResponse {
	/**
	 * @access public
	 * @var Tgc_StrongMail_GetResponse[]
	 */
	public $getResponse;
}}

if (!class_exists("Tgc_StrongMail_GetResponse", false)) {
/**
 * Tgc_StrongMail_GetResponse
 */
class Tgc_StrongMail_GetResponse extends Tgc_StrongMail_Response {
	/**
	 * @access public
	 * @var Tgc_StrongMail_BaseObject
	 */
	public $baseObject;
}}

if (!class_exists("Tgc_StrongMail_GetDataSourceRecordsResponse", false)) {
/**
 * Tgc_StrongMail_GetDataSourceRecordsResponse
 */
class Tgc_StrongMail_GetDataSourceRecordsResponse extends Tgc_StrongMail_GetRecordsResponse {
	/**
	 * @access public
	 * @var Tgc_StrongMail_DataSourceRecord[]
	 */
	public $dataSourceRecord;
}}

if (!class_exists("Tgc_StrongMail_AddProgramContactRecordsResponse", false)) {
/**
 * Tgc_StrongMail_AddProgramContactRecordsResponse
 */
class Tgc_StrongMail_AddProgramContactRecordsResponse extends Tgc_StrongMail_AddRecordsResponse {
	/**
	 * @access public
	 * @var integer
	 */
	public $successCount;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ProgramContactRecord[]
	 */
	public $failureRecord;
	/**
	 * @access public
	 * @var Tgc_StrongMail_ProgramContactRecord[]
	 */
	public $duplicateRecord;
}}

if (!class_exists("MailingService", false)) {
/**
 * MailingService
 * @author WSDLInterpreter
 */
class MailingService extends SoapClient {
	/**
	 * Default class map for wsdl=>php
	 * @access private
	 * @var array
	 */
	private static $classmap = array(
		"GetSingleSignOnURLRequest" => "Tgc_StrongMail_GetSingleSignOnURLRequest",
		"GetSingleSignOnURLResponse" => "Tgc_StrongMail_GetSingleSignOnURLResponse",
		"Response" => "Tgc_StrongMail_Response",
		"DataSourceId" => "Tgc_StrongMail_DataSourceId",
		"ObjectId" => "Tgc_StrongMail_ObjectId",
		"InternalDataSourceId" => "Tgc_StrongMail_InternalDataSourceId",
		"ExternalDataSourceId" => "Tgc_StrongMail_ExternalDataSourceId",
		"InternalDataSourceExtnId" => "Tgc_StrongMail_InternalDataSourceExtnId",
		"DataSource" => "Tgc_StrongMail_DataSource",
		"BaseObject" => "Tgc_StrongMail_BaseObject",
		"InternalDataSource" => "Tgc_StrongMail_InternalDataSource",
		"InternalDataSourceExtn" => "Tgc_StrongMail_InternalDataSourceExtn",
		"ExternalDataSource" => "Tgc_StrongMail_ExternalDataSource",
		"connectionInfo" => "Tgc_StrongMail_connectionInfo",
		"hourlyRefresh" => "Tgc_StrongMail_hourlyRefresh",
		"dailyRefresh" => "Tgc_StrongMail_dailyRefresh",
		"weeklyRefresh" => "Tgc_StrongMail_weeklyRefresh",
		"DataSourceType" => "Tgc_StrongMail_DataSourceType",
		"DatabaseType" => "Tgc_StrongMail_DatabaseType",
		"DataSourceField" => "Tgc_StrongMail_DataSourceField",
		"DataSourceFieldType" => "Tgc_StrongMail_DataSourceFieldType",
		"DataSourceDataType" => "Tgc_StrongMail_DataSourceDataType",
		"DataSourceRecord" => "Tgc_StrongMail_DataSourceRecord",
		"DataSourceOperationStatus" => "Tgc_StrongMail_DataSourceOperationStatus",
		"DataSourceDedupeOption" => "Tgc_StrongMail_DataSourceDedupeOption",
		"DataSourceFilter" => "Tgc_StrongMail_DataSourceFilter",
		"BaseFilter" => "Tgc_StrongMail_BaseFilter",
		"DataSourceOrderBy" => "Tgc_StrongMail_DataSourceOrderBy",
		"AddDataSourceRecordsRequest" => "Tgc_StrongMail_AddDataSourceRecordsRequest",
		"AddRecordsRequest" => "Tgc_StrongMail_AddRecordsRequest",
		"UpsertDataSourceRecordsRequest" => "Tgc_StrongMail_UpsertDataSourceRecordsRequest",
		"UpsertRecordsRequest" => "Tgc_StrongMail_UpsertRecordsRequest",
		"GetDataSourceRecordsRequest" => "Tgc_StrongMail_GetDataSourceRecordsRequest",
		"GetRecordsRequest" => "Tgc_StrongMail_GetRecordsRequest",
		"GetDataSourceRecordsResponse" => "Tgc_StrongMail_GetDataSourceRecordsResponse",
		"GetRecordsResponse" => "Tgc_StrongMail_GetRecordsResponse",
		"RemoveDataSourceRecordsRequest" => "Tgc_StrongMail_RemoveDataSourceRecordsRequest",
		"RemoveRecordsRequest" => "Tgc_StrongMail_RemoveRecordsRequest",
		"ExportDataSourceRecordsRequest" => "Tgc_StrongMail_ExportDataSourceRecordsRequest",
		"ExportRecordsRequest" => "Tgc_StrongMail_ExportRecordsRequest",
		"CopyDataSourceRequest" => "Tgc_StrongMail_CopyDataSourceRequest",
		"CopyRequest" => "Tgc_StrongMail_CopyRequest",
		"DedupeDataSourceRecordsRequest" => "Tgc_StrongMail_DedupeDataSourceRecordsRequest",
		"DedupeRecordsRequest" => "Tgc_StrongMail_DedupeRecordsRequest",
		"DedupeRecordsResponse" => "Tgc_StrongMail_DedupeRecordsResponse",
		"GetDataSourceStatisticsRequest" => "Tgc_StrongMail_GetDataSourceStatisticsRequest",
		"GetStatisticsRequest" => "Tgc_StrongMail_GetStatisticsRequest",
		"GetDataSourceStatisticsResponse" => "Tgc_StrongMail_GetDataSourceStatisticsResponse",
		"GetStatisticsResponse" => "Tgc_StrongMail_GetStatisticsResponse",
		"RefreshRecordsRequest" => "Tgc_StrongMail_RefreshRecordsRequest",
		"RefreshRecordsResponse" => "Tgc_StrongMail_RefreshRecordsResponse",
		"CancelRefreshRecordsRequest" => "Tgc_StrongMail_CancelRefreshRecordsRequest",
		"CancelRefreshRecordsResponse" => "Tgc_StrongMail_CancelRefreshRecordsResponse",
		"TargetId" => "Tgc_StrongMail_TargetId",
		"Target" => "Tgc_StrongMail_Target",
		"TargetType" => "Tgc_StrongMail_TargetType",
		"TargetFilter" => "Tgc_StrongMail_TargetFilter",
		"TargetOrderBy" => "Tgc_StrongMail_TargetOrderBy",
		"CopyTargetRequest" => "Tgc_StrongMail_CopyTargetRequest",
		"ExportTargetRecordsRequest" => "Tgc_StrongMail_ExportTargetRecordsRequest",
		"SuppressionListId" => "Tgc_StrongMail_SuppressionListId",
		"SuppressionList" => "Tgc_StrongMail_SuppressionList",
		"SuppressionFilter" => "Tgc_StrongMail_SuppressionFilter",
		"SuppressionListOrderBy" => "Tgc_StrongMail_SuppressionListOrderBy",
		"AddSuppressionListRecordsRequest" => "Tgc_StrongMail_AddSuppressionListRecordsRequest",
		"CopySuppressionListRequest" => "Tgc_StrongMail_CopySuppressionListRequest",
		"ExportSuppressionListRecordsRequest" => "Tgc_StrongMail_ExportSuppressionListRecordsRequest",
		"RemoveSuppressionListRecordsRequest" => "Tgc_StrongMail_RemoveSuppressionListRecordsRequest",
		"SeedListId" => "Tgc_StrongMail_SeedListId",
		"SeedList" => "Tgc_StrongMail_SeedList",
		"SeedFilter" => "Tgc_StrongMail_SeedFilter",
		"SeedListOrderBy" => "Tgc_StrongMail_SeedListOrderBy",
		"AddSeedListRecordsRequest" => "Tgc_StrongMail_AddSeedListRecordsRequest",
		"CopySeedListRequest" => "Tgc_StrongMail_CopySeedListRequest",
		"ExportSeedListRecordsRequest" => "Tgc_StrongMail_ExportSeedListRecordsRequest",
		"RemoveSeedListRecordsRequest" => "Tgc_StrongMail_RemoveSeedListRecordsRequest",
		"TemplateId" => "Tgc_StrongMail_TemplateId",
		"Template" => "Tgc_StrongMail_Template",
		"TrackingTag" => "Tgc_StrongMail_TrackingTag",
		"OpenTag" => "Tgc_StrongMail_OpenTag",
		"TrackingTagProperties" => "Tgc_StrongMail_TrackingTagProperties",
		"NamedLink" => "Tgc_StrongMail_NamedLink",
		"MessagePart" => "Tgc_StrongMail_MessagePart",
		"MessageFormat" => "Tgc_StrongMail_MessageFormat",
		"MessageType" => "Tgc_StrongMail_MessageType",
		"TemplateFilter" => "Tgc_StrongMail_TemplateFilter",
		"TemplateOrderBy" => "Tgc_StrongMail_TemplateOrderBy",
		"CopyTemplateRequest" => "Tgc_StrongMail_CopyTemplateRequest",
		"ImportMessagePartRequest" => "Tgc_StrongMail_ImportMessagePartRequest",
		"ImportMessagePartResponse" => "Tgc_StrongMail_ImportMessagePartResponse",
		"TestTemplateRequest" => "Tgc_StrongMail_TestTemplateRequest",
		"TestRequest" => "Tgc_StrongMail_TestRequest",
		"ValidateXslRequest" => "Tgc_StrongMail_ValidateXslRequest",
		"ValidateXslResponse" => "Tgc_StrongMail_ValidateXslResponse",
		"FetchLinksRequest" => "Tgc_StrongMail_FetchLinksRequest",
		"FetchLinkResponse" => "Tgc_StrongMail_FetchLinkResponse",
		"FetchLinksResponse" => "Tgc_StrongMail_FetchLinksResponse",
		"BatchResponse" => "Tgc_StrongMail_BatchResponse",
		"FetchLinksTemplateRequest" => "Tgc_StrongMail_FetchLinksTemplateRequest",
		"ContentBlockId" => "Tgc_StrongMail_ContentBlockId",
		"ContentBlock" => "Tgc_StrongMail_ContentBlock",
		"ContentBlockToken" => "Tgc_StrongMail_ContentBlockToken",
		"ContentBlockFilter" => "Tgc_StrongMail_ContentBlockFilter",
		"ContentBlockOrderBy" => "Tgc_StrongMail_ContentBlockOrderBy",
		"CopyContentBlockRequest" => "Tgc_StrongMail_CopyContentBlockRequest",
		"FetchLinksContentBlockRequest" => "Tgc_StrongMail_FetchLinksContentBlockRequest",
		"AttachmentId" => "Tgc_StrongMail_AttachmentId",
		"Attachment" => "Tgc_StrongMail_Attachment",
		"AttachmentFilter" => "Tgc_StrongMail_AttachmentFilter",
		"AttachmentOrderBy" => "Tgc_StrongMail_AttachmentOrderBy",
		"ImportAttachmentContentRequest" => "Tgc_StrongMail_ImportAttachmentContentRequest",
		"ImportContentRequest" => "Tgc_StrongMail_ImportContentRequest",
		"RuleId" => "Tgc_StrongMail_RuleId",
		"Rule" => "Tgc_StrongMail_Rule",
		"RuleValue" => "Tgc_StrongMail_RuleValue",
		"ColumnRuleValue" => "Tgc_StrongMail_ColumnRuleValue",
		"ContentBlockTokenRuleValue" => "Tgc_StrongMail_ContentBlockTokenRuleValue",
		"TextRuleValue" => "Tgc_StrongMail_TextRuleValue",
		"NestedRuleRuleValue" => "Tgc_StrongMail_NestedRuleRuleValue",
		"RuleIfPartCondition" => "Tgc_StrongMail_RuleIfPartCondition",
		"RuleIfPart" => "Tgc_StrongMail_RuleIfPart",
		"RuleThenPart" => "Tgc_StrongMail_RuleThenPart",
		"RuleElsePart" => "Tgc_StrongMail_RuleElsePart",
		"RuleFilter" => "Tgc_StrongMail_RuleFilter",
		"RuleOrderBy" => "Tgc_StrongMail_RuleOrderBy",
		"CopyRuleRequest" => "Tgc_StrongMail_CopyRuleRequest",
		"MailingId" => "Tgc_StrongMail_MailingId",
		"StandardMailingId" => "Tgc_StrongMail_StandardMailingId",
		"TransactionalMailingId" => "Tgc_StrongMail_TransactionalMailingId",
		"Mailing" => "Tgc_StrongMail_Mailing",
		"SchedulableMailing" => "Tgc_StrongMail_SchedulableMailing",
		"schedule" => "Tgc_StrongMail_schedule",
		"recurrence" => "Tgc_StrongMail_recurrence",
		"minutelyRecurrence" => "Tgc_StrongMail_minutelyRecurrence",
		"hourlyRecurrence" => "Tgc_StrongMail_hourlyRecurrence",
		"dailyRecurrence" => "Tgc_StrongMail_dailyRecurrence",
		"weeklyRecurrence" => "Tgc_StrongMail_weeklyRecurrence",
		"monthlyByDateRecurrence" => "Tgc_StrongMail_monthlyByDateRecurrence",
		"monthlyByDayRecurrence" => "Tgc_StrongMail_monthlyByDayRecurrence",
		"yearlyByDateRecurrence" => "Tgc_StrongMail_yearlyByDateRecurrence",
		"yearlyByDayRecurrence" => "Tgc_StrongMail_yearlyByDayRecurrence",
		"StandardMailing" => "Tgc_StrongMail_StandardMailing",
		"TransactionalMailing" => "Tgc_StrongMail_TransactionalMailing",
		"MailingStatus" => "Tgc_StrongMail_MailingStatus",
		"MailingType" => "Tgc_StrongMail_MailingType",
		"MailingPriority" => "Tgc_StrongMail_MailingPriority",
		"MinutelyInterval" => "Tgc_StrongMail_MinutelyInterval",
		"HourlyInterval" => "Tgc_StrongMail_HourlyInterval",
		"DailyInterval" => "Tgc_StrongMail_DailyInterval",
		"WeeklyInterval" => "Tgc_StrongMail_WeeklyInterval",
		"MonthlyInterval" => "Tgc_StrongMail_MonthlyInterval",
		"DailyOccurrence" => "Tgc_StrongMail_DailyOccurrence",
		"WeeklyOccurrence" => "Tgc_StrongMail_WeeklyOccurrence",
		"MailingFilter" => "Tgc_StrongMail_MailingFilter",
		"MailingOrderBy" => "Tgc_StrongMail_MailingOrderBy",
		"AssetExpiryInterval" => "Tgc_StrongMail_AssetExpiryInterval",
		"CopyMailingRequest" => "Tgc_StrongMail_CopyMailingRequest",
		"CancelRequest" => "Tgc_StrongMail_CancelRequest",
		"CancelResponse" => "Tgc_StrongMail_CancelResponse",
		"CloseRequest" => "Tgc_StrongMail_CloseRequest",
		"CloseResponse" => "Tgc_StrongMail_CloseResponse",
		"ArchiveRequest" => "Tgc_StrongMail_ArchiveRequest",
		"ArchiveResponse" => "Tgc_StrongMail_ArchiveResponse",
		"GetMailingStatisticsRequest" => "Tgc_StrongMail_GetMailingStatisticsRequest",
		"GetMailingStatisticsResponse" => "Tgc_StrongMail_GetMailingStatisticsResponse",
		"LaunchRequest" => "Tgc_StrongMail_LaunchRequest",
		"LaunchResponse" => "Tgc_StrongMail_LaunchResponse",
		"LoadRequest" => "Tgc_StrongMail_LoadRequest",
		"LoadResponse" => "Tgc_StrongMail_LoadResponse",
		"PauseRequest" => "Tgc_StrongMail_PauseRequest",
		"PauseResponse" => "Tgc_StrongMail_PauseResponse",
		"ResumeRequest" => "Tgc_StrongMail_ResumeRequest",
		"ResumeResponse" => "Tgc_StrongMail_ResumeResponse",
		"ScheduleRequest" => "Tgc_StrongMail_ScheduleRequest",
		"ScheduleResponse" => "Tgc_StrongMail_ScheduleResponse",
		"SendRecord" => "Tgc_StrongMail_SendRecord",
		"SendRequest" => "Tgc_StrongMail_SendRequest",
		"SendResponse" => "Tgc_StrongMail_SendResponse",
		"GetTxnMailingHandleRequest" => "Tgc_StrongMail_GetTxnMailingHandleRequest",
		"GetTxnMailingHandleResponse" => "Tgc_StrongMail_GetTxnMailingHandleResponse",
		"TxnSendRequest" => "Tgc_StrongMail_TxnSendRequest",
		"TxnSendResponse" => "Tgc_StrongMail_TxnSendResponse",
		"GetTxnEasInfoRequest" => "Tgc_StrongMail_GetTxnEasInfoRequest",
		"GetTxnEasInfoResponse" => "Tgc_StrongMail_GetTxnEasInfoResponse",
		"GetAllEasByMailingIdRequest" => "Tgc_StrongMail_GetAllEasByMailingIdRequest",
		"GetAllEasListByMailingIdResponse" => "Tgc_StrongMail_GetAllEasListByMailingIdResponse",
		"GetAllEasByMailingIdResponse" => "Tgc_StrongMail_GetAllEasByMailingIdResponse",
		"TestMailingRequest" => "Tgc_StrongMail_TestMailingRequest",
		"ProgramId" => "Tgc_StrongMail_ProgramId",
		"ProgramContactRecord" => "Tgc_StrongMail_ProgramContactRecord",
		"AddProgramContactRecordsRequest" => "Tgc_StrongMail_AddProgramContactRecordsRequest",
		"AddProgramContactRecordsResponse" => "Tgc_StrongMail_AddProgramContactRecordsResponse",
		"AddRecordsResponse" => "Tgc_StrongMail_AddRecordsResponse",
		"OrganizationId" => "Tgc_StrongMail_OrganizationId",
		"Organization" => "Tgc_StrongMail_Organization",
		"OrganizationFilter" => "Tgc_StrongMail_OrganizationFilter",
		"OrganizationOrderBy" => "Tgc_StrongMail_OrganizationOrderBy",
		"UserId" => "Tgc_StrongMail_UserId",
		"User" => "Tgc_StrongMail_User",
		"access" => "Tgc_StrongMail_access",
		"UserFilter" => "Tgc_StrongMail_UserFilter",
		"UserOrderBy" => "Tgc_StrongMail_UserOrderBy",
		"RolePermissions" => "Tgc_StrongMail_RolePermissions",
		"Permissions" => "Tgc_StrongMail_Permissions",
		"RoleId" => "Tgc_StrongMail_RoleId",
		"Role" => "Tgc_StrongMail_Role",
		"RoleFilter" => "Tgc_StrongMail_RoleFilter",
		"RoleOrderBy" => "Tgc_StrongMail_RoleOrderBy",
		"AssignedRoleId" => "Tgc_StrongMail_AssignedRoleId",
		"AssignedRole" => "Tgc_StrongMail_AssignedRole",
		"AssignedRoleOrderBy" => "Tgc_StrongMail_AssignedRoleOrderBy",
		"AssignedRoleFilter" => "Tgc_StrongMail_AssignedRoleFilter",
		"SystemAddressId" => "Tgc_StrongMail_SystemAddressId",
		"SystemAddress" => "Tgc_StrongMail_SystemAddress",
		"SystemAddressType" => "Tgc_StrongMail_SystemAddressType",
		"DataSourceImportFrequency" => "Tgc_StrongMail_DataSourceImportFrequency",
		"DataSourceImportMode" => "Tgc_StrongMail_DataSourceImportMode",
		"SystemAddressFilter" => "Tgc_StrongMail_SystemAddressFilter",
		"SystemAddressOrderBy" => "Tgc_StrongMail_SystemAddressOrderBy",
		"CampaignId" => "Tgc_StrongMail_CampaignId",
		"Campaign" => "Tgc_StrongMail_Campaign",
		"CampaignFilter" => "Tgc_StrongMail_CampaignFilter",
		"CampaignOrderBy" => "Tgc_StrongMail_CampaignOrderBy",
		"MediaServerId" => "Tgc_StrongMail_MediaServerId",
		"MediaServer" => "Tgc_StrongMail_MediaServer",
		"server" => "Tgc_StrongMail_server",
		"MediaServerFilter" => "Tgc_StrongMail_MediaServerFilter",
		"MediaServerOrderBy" => "Tgc_StrongMail_MediaServerOrderBy",
		"WebAnalyticsId" => "Tgc_StrongMail_WebAnalyticsId",
		"WebAnalytics" => "Tgc_StrongMail_WebAnalytics",
		"WebAnalyticsFilter" => "Tgc_StrongMail_WebAnalyticsFilter",
		"WebAnalyticsOrderBy" => "Tgc_StrongMail_WebAnalyticsOrderBy",
		"MailingClassId" => "Tgc_StrongMail_MailingClassId",
		"MailingClass" => "Tgc_StrongMail_MailingClass",
		"MailingClassFilter" => "Tgc_StrongMail_MailingClassFilter",
		"MailingClassOrderBy" => "Tgc_StrongMail_MailingClassOrderBy",
		"Forward2FriendOfferTrackingOption" => "Tgc_StrongMail_Forward2FriendOfferTrackingOption",
		"StrongtoolOpenAs" => "Tgc_StrongMail_StrongtoolOpenAs",
		"StrongtoolId" => "Tgc_StrongMail_StrongtoolId",
		"Strongtool" => "Tgc_StrongMail_Strongtool",
		"StrongtoolOrderBy" => "Tgc_StrongMail_StrongtoolOrderBy",
		"StrongtoolFilter" => "Tgc_StrongMail_StrongtoolFilter",
		"OrganizationToken" => "Tgc_StrongMail_OrganizationToken",
		"IsSSO" => "Tgc_StrongMail_IsSSO",
		"DayOfWeek" => "Tgc_StrongMail_DayOfWeek",
		"DayOfMonth" => "Tgc_StrongMail_DayOfMonth",
		"Month" => "Tgc_StrongMail_Month",
		"NameValuePair" => "Tgc_StrongMail_NameValuePair",
		"Token" => "Tgc_StrongMail_Token",
		"FilterBooleanScalarOperator" => "Tgc_StrongMail_FilterBooleanScalarOperator",
		"FilterIdScalarOperator" => "Tgc_StrongMail_FilterIdScalarOperator",
		"FilterIntegerScalarOperator" => "Tgc_StrongMail_FilterIntegerScalarOperator",
		"FilterStringScalarOperator" => "Tgc_StrongMail_FilterStringScalarOperator",
		"FilterArrayOperator" => "Tgc_StrongMail_FilterArrayOperator",
		"FilterCondition" => "Tgc_StrongMail_FilterCondition",
		"BooleanFilterCondition" => "Tgc_StrongMail_BooleanFilterCondition",
		"ScalarBooleanFilterCondition" => "Tgc_StrongMail_ScalarBooleanFilterCondition",
		"IntegerFilterCondition" => "Tgc_StrongMail_IntegerFilterCondition",
		"ScalarIntegerFilterCondition" => "Tgc_StrongMail_ScalarIntegerFilterCondition",
		"ArrayIntegerFilterCondition" => "Tgc_StrongMail_ArrayIntegerFilterCondition",
		"IdFilterCondition" => "Tgc_StrongMail_IdFilterCondition",
		"ScalarIdFilterCondition" => "Tgc_StrongMail_ScalarIdFilterCondition",
		"ArrayIdFilterCondition" => "Tgc_StrongMail_ArrayIdFilterCondition",
		"StringFilterCondition" => "Tgc_StrongMail_StringFilterCondition",
		"ScalarStringFilterCondition" => "Tgc_StrongMail_ScalarStringFilterCondition",
		"ArrayStringFilterCondition" => "Tgc_StrongMail_ArrayStringFilterCondition",
		"ComparisonOperation" => "Tgc_StrongMail_ComparisonOperation",
		"LogicalOperation" => "Tgc_StrongMail_LogicalOperation",
		"UpsertRecordsResponse" => "Tgc_StrongMail_UpsertRecordsResponse",
		"CopyResponse" => "Tgc_StrongMail_CopyResponse",
		"CreateRequest" => "Tgc_StrongMail_CreateRequest",
		"BatchCreateResponse" => "Tgc_StrongMail_BatchCreateResponse",
		"CreateResponse" => "Tgc_StrongMail_CreateResponse",
		"ExportRecordsResponse" => "Tgc_StrongMail_ExportRecordsResponse",
		"DeleteRequest" => "Tgc_StrongMail_DeleteRequest",
		"BatchDeleteResponse" => "Tgc_StrongMail_BatchDeleteResponse",
		"DeleteResponse" => "Tgc_StrongMail_DeleteResponse",
		"GetRequest" => "Tgc_StrongMail_GetRequest",
		"BatchGetResponse" => "Tgc_StrongMail_BatchGetResponse",
		"GetResponse" => "Tgc_StrongMail_GetResponse",
		"BatchUpdateResponse" => "Tgc_StrongMail_BatchUpdateResponse",
		"ImportContentResponse" => "Tgc_StrongMail_ImportContentResponse",
		"ListRequest" => "Tgc_StrongMail_ListRequest",
		"ListResponse" => "Tgc_StrongMail_ListResponse",
		"RemoveRecordsResponse" => "Tgc_StrongMail_RemoveRecordsResponse",
		"TestResponse" => "Tgc_StrongMail_TestResponse",
		"UpdateRequest" => "Tgc_StrongMail_UpdateRequest",
		"UpdateResponse" => "Tgc_StrongMail_UpdateResponse",
		"CharSet" => "Tgc_StrongMail_CharSet",
		"Encoding" => "Tgc_StrongMail_Encoding",
		"FaultCode" => "Tgc_StrongMail_FaultCode",
		"FaultMessage" => "Tgc_StrongMail_FaultMessage",
		"FaultDetail" => "Tgc_StrongMail_FaultDetail",
		"AuthorizationFaultDetail" => "Tgc_StrongMail_AuthorizationFaultDetail",
		"ConcurrentModificationFaultDetail" => "Tgc_StrongMail_ConcurrentModificationFaultDetail",
		"InvalidObjectFaultDetail" => "Tgc_StrongMail_InvalidObjectFaultDetail",
		"ObjectNotFoundFaultDetail" => "Tgc_StrongMail_ObjectNotFoundFaultDetail",
		"StaleObjectFaultDetail" => "Tgc_StrongMail_StaleObjectFaultDetail",
		"UnexpectedFaultDetail" => "Tgc_StrongMail_UnexpectedFaultDetail",
		"UnrecognizedObjectTypeFaultDetail" => "Tgc_StrongMail_UnrecognizedObjectTypeFaultDetail",
		"BadHandleFaultDetail" => "Tgc_StrongMail_BadHandleFaultDetail",
	);

	/**
	 * Constructor using wsdl location and options array
	 * @param string $wsdl WSDL location for this service
	 * @param array $options Options for the SoapClient
	 */
	public function __construct($wsdl="https://67.129.116.186/sm/services/mailing/2009/03/02?wsdl", $options=array()) {
		foreach(self::$classmap as $wsdlClassName => $phpClassName) {
		    if(!isset($options['classmap'][$wsdlClassName])) {
		        $options['classmap'][$wsdlClassName] = $phpClassName;
		    }
		}
		parent::__construct($wsdl, $options);
	}

		private function splitTypesString($arr)
		{
		  $tempArray = split('[\)\(]+', $arr);
		  unset($tempArray[count($tempArray)-1]);
		  unset($tempArray[0]);
		  return array_values($tempArray);
		}

	/**
	 * Checks if an argument list matches against a valid argument type list
	 * @param array $arguments The argument list to check
	 * @param array $validParameters A list of valid argument types
	 * @return boolean true if arguments match against validParameters
	 * @throws Exception invalid function signature message
	 */
	public function _checkArguments($arguments, $validParameters)
		{
		  $variables = "";
		  foreach ($arguments as $arg)
		  {
		    $type = gettype($arg);
		    if ($type == "object")
		    {
		      $type = get_class($arg);
		    }
		    $variables .= "(".$type.")";
		  }

		  if (!in_array($variables, $validParameters))
		  {
		    // Check for superclasses
		    $myVarArray = $this->splitTypesString($variables);

		    foreach ($validParameters as $vP)
		    {
		      $myParamArray = $this->splitTypesString($vP);

		      if (count($myVarArray) != count($myParamArray))
		      {
		        continue;
		      }

		      $matches = 0;
		      for ($i=0; $i<count($myParamArray); $i++)
		      {
		        if (class_exists($myVarArray[$i]) && class_exists($myParamArray[$i]))
		        {
		          $reflectionClass1 = new ReflectionClass($myVarArray[$i]);
		          $reflectionClass2 = new ReflectionClass($myParamArray[$i]);

		          if ($reflectionClass1->isSubclassOf($reflectionClass2))
		          {
		            $matches++;
		          }
		        }
		        else
		        {
		          if ($myVarArray[$i] == $myParamArray[$i])
		          {
		            $matches++;
		          }
		        }
		      }

		      if ($matches == count($myParamArray))
		      {
		        return true;
		      }
		    }
		    throw new Exception("Invalid parameter types: ".str_replace(")(", ", ", $variables));
		  }
		  return true;
	}

	/**
	 * Service Call: addRecords
	 * Parameter options:
	 * (Tgc_StrongMail_AddRecordsRequest) addRecords
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_AddRecordsResponse
	 * @throws Exception invalid function signature message
	 */
	public function addRecords($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_AddRecordsRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("addRecords", $args);
	}


	/**
	 * Service Call: archive
	 * Parameter options:
	 * (Tgc_StrongMail_ArchiveRequest) archive
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_ArchiveResponse
	 * @throws Exception invalid function signature message
	 */
	public function archive($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_ArchiveRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("archive", $args);
	}


	/**
	 * Service Call: cancel
	 * Parameter options:
	 * (Tgc_StrongMail_CancelRequest) cancel
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_CancelResponse
	 * @throws Exception invalid function signature message
	 */
	public function cancel($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_CancelRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("cancel", $args);
	}


	/**
	 * Service Call: cancelRefreshRecords
	 * Parameter options:
	 * (Tgc_StrongMail_CancelRefreshRecordsRequest) cancelRefreshRecords
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_CancelRefreshRecordsResponse
	 * @throws Exception invalid function signature message
	 */
	public function cancelRefreshRecords($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_CancelRefreshRecordsRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("cancelRefreshRecords", $args);
	}


	/**
	 * Service Call: close
	 * Parameter options:
	 * (Tgc_StrongMail_CloseRequest) close
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_CloseResponse
	 * @throws Exception invalid function signature message
	 */
	public function close($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_CloseRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("close", $args);
	}


	/**
	 * Service Call: copy
	 * Parameter options:
	 * (Tgc_StrongMail_CopyRequest) copy
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_CopyResponse
	 * @throws Exception invalid function signature message
	 */
	public function copy($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_CopyRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("copy", $args);
	}


	/**
	 * Service Call: create
	 * Parameter options:
	 * (Tgc_StrongMail_CreateRequest) create
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_BatchCreateResponse
	 * @throws Exception invalid function signature message
	 */
	public function create($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_CreateRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("create", $args);
	}


	/**
	 * Service Call: dedupeRecords
	 * Parameter options:
	 * (Tgc_StrongMail_DedupeRecordsRequest) dedupeRecords
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_DedupeRecordsResponse
	 * @throws Exception invalid function signature message
	 */
	public function dedupeRecords($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_DedupeRecordsRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("dedupeRecords", $args);
	}


	/**
	 * Service Call: delete
	 * Parameter options:
	 * (Tgc_StrongMail_DeleteRequest) delete
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_BatchDeleteResponse
	 * @throws Exception invalid function signature message
	 */
	public function delete($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_DeleteRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("delete", $args);
	}


	/**
	 * Service Call: exportRecords
	 * Parameter options:
	 * (Tgc_StrongMail_ExportRecordsRequest) exportRecords
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_ExportRecordsResponse
	 * @throws Exception invalid function signature message
	 */
	public function exportRecords($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_ExportRecordsRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("exportRecords", $args);
	}


	/**
	 * Service Call: get
	 * Parameter options:
	 * (Tgc_StrongMail_GetRequest) get
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_BatchGetResponse
	 * @throws Exception invalid function signature message
	 */
	public function get($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_GetRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("get", $args);
	}


	/**
	 * Service Call: getStatistics
	 * Parameter options:
	 * (Tgc_StrongMail_GetStatisticsRequest) getStatistics
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_GetStatisticsResponse
	 * @throws Exception invalid function signature message
	 */
	public function getStatistics($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_GetStatisticsRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("getStatistics", $args);
	}


	/**
	 * Service Call: importContent
	 * Parameter options:
	 * (Tgc_StrongMail_ImportContentRequest) importContent
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_ImportContentResponse
	 * @throws Exception invalid function signature message
	 */
	public function importContent($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_ImportContentRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("importContent", $args);
	}


	/**
	 * Service Call: importMessagePart
	 * Parameter options:
	 * (Tgc_StrongMail_ImportMessagePartRequest) importMessagePart
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_ImportMessagePartResponse
	 * @throws Exception invalid function signature message
	 */
	public function importMessagePart($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_ImportMessagePartRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("importMessagePart", $args);
	}


	/**
	 * Service Call: launch
	 * Parameter options:
	 * (Tgc_StrongMail_LaunchRequest) launch
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_LaunchResponse
	 * @throws Exception invalid function signature message
	 */
	public function launch($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_LaunchRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("launch", $args);
	}


	/**
	 * Service Call: _list
	 * Parameter options:
	 * (Tgc_StrongMail_ListRequest) list
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_ListResponse
	 * @throws Exception invalid function signature message
	 */
	public function _list($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_ListRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("list", $args);
	}


	/**
	 * Service Call: load
	 * Parameter options:
	 * (Tgc_StrongMail_LoadRequest) load
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_LoadResponse
	 * @throws Exception invalid function signature message
	 */
	public function load($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_LoadRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("load", $args);
	}


	/**
	 * Service Call: pause
	 * Parameter options:
	 * (Tgc_StrongMail_PauseRequest) pause
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_PauseResponse
	 * @throws Exception invalid function signature message
	 */
	public function pause($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_PauseRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("pause", $args);
	}


	/**
	 * Service Call: refreshRecords
	 * Parameter options:
	 * (Tgc_StrongMail_RefreshRecordsRequest) refreshRecords
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_RefreshRecordsResponse
	 * @throws Exception invalid function signature message
	 */
	public function refreshRecords($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_RefreshRecordsRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("refreshRecords", $args);
	}


	/**
	 * Service Call: removeRecords
	 * Parameter options:
	 * (Tgc_StrongMail_RemoveRecordsRequest) removeRecords
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_RemoveRecordsResponse
	 * @throws Exception invalid function signature message
	 */
	public function removeRecords($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_RemoveRecordsRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("removeRecords", $args);
	}


	/**
	 * Service Call: resume
	 * Parameter options:
	 * (Tgc_StrongMail_ResumeRequest) resume
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_ResumeResponse
	 * @throws Exception invalid function signature message
	 */
	public function resume($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_ResumeRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("resume", $args);
	}


	/**
	 * Service Call: schedule
	 * Parameter options:
	 * (Tgc_StrongMail_ScheduleRequest) schedule
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_ScheduleResponse
	 * @throws Exception invalid function signature message
	 */
	public function schedule($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_ScheduleRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("schedule", $args);
	}


	/**
	 * Service Call: send
	 * Parameter options:
	 * (Tgc_StrongMail_SendRequest) send
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_SendResponse
	 * @throws Exception invalid function signature message
	 */
	public function send($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_SendRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("send", $args);
	}


	/**
	 * Service Call: getTxnMailingHandle
	 * Parameter options:
	 * (Tgc_StrongMail_GetTxnMailingHandleRequest) getTxnMailingHandle
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_GetTxnMailingHandleResponse
	 * @throws Exception invalid function signature message
	 */
	public function getTxnMailingHandle($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_GetTxnMailingHandleRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("getTxnMailingHandle", $args);
	}


	/**
	 * Service Call: txnSend
	 * Parameter options:
	 * (Tgc_StrongMail_TxnSendRequest) txnSend
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_TxnSendResponse
	 * @throws Exception invalid function signature message
	 */
	public function txnSend($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_TxnSendRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("txnSend", $args);
	}


	/**
	 * Service Call: getTxnEasInfo
	 * Parameter options:
	 * (Tgc_StrongMail_GetTxnEasInfoRequest) getTxnEasInfo
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_GetTxnEasInfoResponse
	 * @throws Exception invalid function signature message
	 */
	public function getTxnEasInfo($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_GetTxnEasInfoRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("getTxnEasInfo", $args);
	}


	/**
	 * Service Call: test
	 * Parameter options:
	 * (Tgc_StrongMail_TestRequest) test
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_TestResponse
	 * @throws Exception invalid function signature message
	 */
	public function test($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_TestRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("test", $args);
	}


	/**
	 * Service Call: update
	 * Parameter options:
	 * (Tgc_StrongMail_UpdateRequest) update
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_BatchUpdateResponse
	 * @throws Exception invalid function signature message
	 */
	public function update($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_UpdateRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("update", $args);
	}


	/**
	 * Service Call: upsertRecord
	 * Parameter options:
	 * (Tgc_StrongMail_UpsertRecordsRequest) upsertRecords
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_UpsertRecordsResponse
	 * @throws Exception invalid function signature message
	 */
	public function upsertRecord($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_UpsertRecordsRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("upsertRecord", $args);
	}


	/**
	 * Service Call: getRecords
	 * Parameter options:
	 * (Tgc_StrongMail_GetRecordsRequest) getRecords
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_GetRecordsResponse
	 * @throws Exception invalid function signature message
	 */
	public function getRecords($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_GetRecordsRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("getRecords", $args);
	}


	/**
	 * Service Call: validateXsl
	 * Parameter options:
	 * (Tgc_StrongMail_ValidateXslRequest) validateXsl
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_ValidateXslResponse
	 * @throws Exception invalid function signature message
	 */
	public function validateXsl($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_ValidateXslRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("validateXsl", $args);
	}


	/**
	 * Service Call: fetchLinks
	 * Parameter options:
	 * (Tgc_StrongMail_FetchLinksRequest) fetchLinks
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_FetchLinksResponse
	 * @throws Exception invalid function signature message
	 */
	public function fetchLinks($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_FetchLinksRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("fetchLinks", $args);
	}


	/**
	 * Service Call: getSingleSignOnURL
	 * Parameter options:
	 * (Tgc_StrongMail_GetSingleSignOnURLRequest) getSingleSignOnURL
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_GetSingleSignOnURLResponse
	 * @throws Exception invalid function signature message
	 */
	public function getSingleSignOnURL($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_GetSingleSignOnURLRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("getSingleSignOnURL", $args);
	}


	/**
	 * Service Call: GetAllEasByMailingId
	 * Parameter options:
	 * (Tgc_StrongMail_GetAllEasByMailingIdRequest) GetAllEasByMailingId
	 * @param mixed,... See function description for parameter options
	 * @return Tgc_StrongMail_GetAllEasListByMailingIdResponse
	 * @throws Exception invalid function signature message
	 */
	public function GetAllEasByMailingId($mixed = null) {
		$validParameters = array(
			"(Tgc_StrongMail_GetAllEasByMailingIdRequest)",
		);
		$args = func_get_args();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("GetAllEasByMailingId", $args);
	}


}}

?>