<?php
/*
 *********************************************************************************************************
 * daloRADIUS - RADIUS Web 平臺
 * Copyright (C) 2007 - Liran Tal <liran@enginx.com> 版權所有。
 *
 *這個程式是自由軟體;你可以和/或重新分配
 *修改它根據GNU通用公共許可證
 *由自由軟體基金會發佈,版本2
 *的許可,或者(在您的選擇)任何後來的版本。
 *
 *你應該收到了GNU通用公共許可證的副本
 *連同這個程式;如果不是,寫信給自由軟體
 *基礎,Inc . 59寺廟的地方——330套房,波士頓,MA 02111 - 1307,美國
 *
 *********************************************************************************************************
 * 描述:
 *		中文語言檔
 * 作者：Liran Tal <liran@enginx.com>
 * 漢化作者:	三多 <10644331064@qq.com>
 * 適用版本:	0.9.9
 *
 *********************************************************************************************************
 */
 
$l['all']['daloRADIUS'] = "daloRADIUS " . $configValues['DALORADIUS_VERSION'];
$l['all']['daloRADIUSVersion'] = "版本 " . $configValues['DALORADIUS_VERSION'];
$l['all']['copyright1'] = "RADIUS 管理、報告、會計和帳單<a href=\"https://github.com/lirantal/daloradius\">Liran Tal</a>";
$l['all']['copyright2'] = "daloRADIUS Copyright &copy; 2007-2019 by Liran Tal of Produced by SanDuo Chinese language pack</a>.";
$l['all']['ID'] = "ID";
$l['all']['PoolName'] = "IP地址名稱";
$l['all']['CalledStationId'] = "被叫號碼";
$l['all']['CallingStationID'] = "被叫號碼";
$l['all']['ExpiryTime'] = "到期時間";
$l['all']['PoolKey'] = "池秘鑰";

/********************************************************************************/
/* 設備屬性相關的翻譯                                     */
/********************************************************************************/
$l['all']['Dictionary'] = "字典";
$l['all']['VendorID'] = "設備代碼";
$l['all']['VendorName'] = "設備名稱";
$l['all']['VendorAttribute'] = "所屬設備";
$l['all']['RecommendedOP'] = "推薦人";
$l['all']['RecommendedTable'] = "推薦表";
$l['all']['RecommendedTooltip'] = "推薦工具提示";
$l['all']['RecommendedHelper'] = "推薦助手";
/***********************************************************************************/

$l['all']['CSVData'] = "CSV格式資料";

$l['all']['CPU'] = "CPU";

/* ****************************** radius的相關文本 ******************************* */
$l['all']['RADIUSDictionaryPath'] = "RADIUS字典路徑";


$l['all']['DashboardSecretKey'] = "儀錶盤金鑰";
$l['all']['DashboardDebug'] = "調試";
$l['all']['DashboardDelaySoft'] = "在幾分鐘的時間來考慮一個‘軟’延遲限制";
$l['all']['DashboardDelayHard'] = "在幾分鐘的時間來考慮一個‘硬’延遲限制";



$l['all']['SendWelcomeNotification'] = "歡迎發送通知";
$l['all']['SMTPServerAddress'] = "SMTP伺服器地址";
$l['all']['SMTPServerPort'] = "SMTP伺服器埠";
$l['all']['SMTPServerFromEmail'] = "寄件者郵寄地址";

$l['all']['customAttributes'] = "使用者屬性";

$l['all']['UserType'] = "用戶類型";

$l['all']['BatchName'] = "批量名稱";
$l['all']['BatchStatus'] = "批量狀態";

$l['all']['Users'] = "用戶";

$l['all']['Compare'] = "比較";
$l['all']['Never'] = "從不";


$l['all']['Section'] = "部門";
$l['all']['Item'] = "項目";

$l['all']['Megabytes'] = "MB";
$l['all']['Gigabytes'] = "GB";

$l['all']['Daily'] = "每日";
$l['all']['Weekly'] = "每週";
$l['all']['Monthly'] = "每月";
$l['all']['Yearly'] = "每年";

$l['all']['Month'] = "月";

$l['all']['RemoveRadacctRecords'] = "刪除帳單記錄";

$l['all']['CleanupSessions'] = "清理會話年齡比";
$l['all']['DeleteSessions'] = "刪除會話年齡比";

$l['all']['StartingDate'] = "開始日期";
$l['all']['EndingDate'] = "結束日期";

$l['all']['Realm'] = "域";
$l['all']['RealmName'] = "功能變數名稱";
$l['all']['RealmSecret'] = "域安全";
$l['all']['AuthHost'] = "認證主機";
$l['all']['AcctHost'] = "統計主機";
$l['all']['Ldflag'] = "ld標識";
$l['all']['Nostrip'] = "分佈IP";
$l['all']['Notrealm'] = "非域";
$l['all']['Hints'] = "提示";

$l['all']['Proxy'] = "代理";
$l['all']['ProxyName'] = "代理名稱";
$l['all']['ProxySecret'] = "代理安全";
$l['all']['DeadTime'] = "停滯時間";
$l['all']['RetryDelay'] = "延遲重試";
$l['all']['RetryCount'] = "重試次數";
$l['all']['DefaultFallback'] = "默認後退";


$l['all']['Firmware'] = "固件";
$l['all']['NASMAC'] = "NAS MAC";

$l['all']['WanIface'] = "廣域網路網路介面";
$l['all']['WanMAC'] = "廣域網路MAC地址";
$l['all']['WanIP'] = "廣域網路IP地址";
$l['all']['WanGateway'] = "廣域網路閘道";

$l['all']['LanIface'] = "局域網網路介面";
$l['all']['LanMAC'] = "局域網MAC位址";
$l['all']['LanIP'] = "局域網IP位址";

$l['all']['WifiIface'] = "無線網網路介面";
$l['all']['WifiMAC'] = "無線網MAC地址";
$l['all']['WifiIP'] = "無線網IP地址";

$l['all']['WifiSSID'] = "無線網網路名稱";
$l['all']['WifiKey'] = "無線網金鑰";
$l['all']['WifiChannel'] = "無線網頻道";

$l['all']['CheckinTime'] = "最後登錄";

$l['all']['FramedIPAddress'] = "用戶IP地址";
$l['all']['SimultaneousUse'] = "同時使用";
$l['all']['HgID'] = "尋線群ID";
$l['all']['Hg'] = "尋線群";
$l['all']['HgIPHost'] = "尋線群IP/主機";
$l['all']['HgGroupName'] = "尋線群組名稱";
$l['all']['HgPortId'] = "尋線群埠名稱";
$l['all']['NasID'] = "NAS ID";
$l['all']['Nas'] = "NAS ";
$l['all']['NasIPHost'] = "NAS IP/主機";
$l['all']['NasShortname'] = "NAS 簡稱";
$l['all']['NasType'] = "NAS類型";
$l['all']['NasPorts'] = "NAS埠";
$l['all']['NasSecret'] = "NAS安全";
$l['all']['NasCommunity'] = "NAS組";
$l['all']['NasDescription'] = "NAS描述";
$l['all']['PacketType'] = "資料包類型";
$l['all']['HotSpot'] = "熱點";
$l['all']['HotSpots'] = "熱點";
$l['all']['HotSpotName'] = "熱點名稱";
$l['all']['Name'] = "名稱";
$l['all']['Username'] = "用戶名";
$l['all']['Password'] = "密碼";
$l['all']['PasswordType'] = "密碼類型";
$l['all']['IPAddress'] = "IP地址";
$l['all']['Profile'] = "使用者設定檔";
$l['all']['Group'] = "組";
$l['all']['Groupname'] = "組名稱";
$l['all']['ProfilePriority'] = "優先的設定檔";
$l['all']['GroupPriority'] = "優先的組";
$l['all']['CurrentGroupname'] = "萬用群組名稱";
$l['all']['NewGroupname'] = "新建組名稱";
$l['all']['Priority'] = "優先";
$l['all']['Attribute'] = "屬性";
$l['all']['Operator'] = "操作員";
$l['all']['Value'] = "值";
$l['all']['NewValue'] = "新建值";
$l['all']['MaxTimeExpiration'] = "最大時間/有效期";
$l['all']['UsedTime'] = "使用時間";
$l['all']['Status'] = "狀態";
$l['all']['Usage'] = "使用";
$l['all']['StartTime'] = "登陸時間";
$l['all']['StopTime'] = "停止時間";
$l['all']['TotalTime'] = "總時間";
$l['all']['TotalTraffic'] = "總流量";
$l['all']['Bytes'] = "位元組";
$l['all']['Upload'] = "上傳";
$l['all']['Download'] = "下載";
$l['all']['Rollback'] = "回滾";
$l['all']['Termination'] = "終止";
$l['all']['NASIPAddress'] = "NAS IP地址";
$l['all']['NASShortName'] = "NAS簡稱";
$l['all']['Action'] = "活動";
$l['all']['UniqueUsers'] = "獨立用戶";
$l['all']['TotalHits'] = "總點擊數";
$l['all']['AverageTime'] = "平均時間";
$l['all']['Records'] = "記錄";
$l['all']['Summary'] = "明細";
$l['all']['Statistics'] = "統計";
$l['all']['Credit'] = "信用";
$l['all']['Used'] = "已使用";
$l['all']['LeftTime'] = "剩餘時間";
$l['all']['LeftPercent'] = "%剩餘時間";
$l['all']['TotalSessions'] = "總會話";
$l['all']['LastLoginTime'] = "最後登錄時間";
$l['all']['TotalSessionTime'] = "總會話時間";
$l['all']['RateName'] = "價格名稱";
$l['all']['RateType'] = "價格類型";
$l['all']['RateCost'] = "成本率";//這個詞語有待改進
$l['all']['Billed'] = "記帳";
$l['all']['TotalUsers'] = "總用戶";
$l['all']['ActiveUsers'] = "活動用戶";
$l['all']['TotalBilled'] = "總記帳";
$l['all']['TotalPayed'] = "總支付";
$l['all']['Balance'] = "餘額";
$l['all']['CardBank'] = "銀行卡";
$l['all']['Type'] = "類型";
$l['all']['CardBank'] = "銀行卡";
$l['all']['MACAddress'] = "MAC地址";
$l['all']['Geocode'] = "位址編碼";
$l['all']['PINCode'] = "PIN碼";
$l['all']['CreationDate'] = "創建日期";
$l['all']['CreationBy'] = "創建人";
$l['all']['UpdateDate'] = "更新日期";
$l['all']['UpdateBy'] = "更新人";

$l['all']['Discount'] = "折扣";
$l['all']['BillAmount'] = "記帳總額";
$l['all']['BillAction'] = "記帳功能";
$l['all']['BillPerformer'] = "記帳執行者";
$l['all']['BillReason'] = "記帳原因";
$l['all']['Lead'] = "廣告";
$l['all']['Coupon'] = "優惠券";
$l['all']['OrderTaker'] = "訂單員";
$l['all']['BillStatus'] = "記帳狀態";
$l['all']['LastBill'] = "最後記帳";
$l['all']['NextBill'] = "下次記帳";
$l['all']['BillDue'] = "記帳到期";
$l['all']['NextInvoiceDue'] = "下次應付款帳單";
$l['all']['PostalInvoice'] = "郵寄帳單";
$l['all']['FaxInvoice'] = "傳真帳單";
$l['all']['EmailInvoice'] = "Email帳單";

$l['all']['ClientName'] = "客戶名稱";
$l['all']['Date'] = "日期";

$l['all']['edit'] = "編輯";
$l['all']['del'] = "刪除";
$l['all']['groupslist'] = "群組列表";
$l['all']['TestUser'] = "測試用戶";
$l['all']['Accounting'] = "帳單";
$l['all']['RADIUSReply'] = "使用者狀態";/**RADIUS回復狀態Access-Accept  Access-Request**/

$l['all']['Disconnect'] = "斷開";

$l['all']['Debug'] = "調試";
$l['all']['Timeout'] = "超時";
$l['all']['Retries'] = "重試";
$l['all']['Count'] = "計數";
$l['all']['Requests'] = "請求";

$l['all']['DatabaseHostname'] = "資料庫主機名稱稱";
$l['all']['DatabasePort'] = "資料庫埠號";
$l['all']['DatabaseUser'] = "資料庫用戶名";
$l['all']['DatabasePass'] = "資料庫密碼";
$l['all']['DatabaseName'] = "數據名稱";

$l['all']['PrimaryLanguage'] = "主要語言";

$l['all']['PagesLogging'] = "頁面日誌（訪問頁面）";
$l['all']['QueriesLogging'] = "查詢日誌（報表和圖表）";
$l['all']['ActionsLogging'] = "活動日誌（表單提交）";
$l['all']['FilenameLogging'] = "檔案名日誌（完整路徑）";
$l['all']['LoggingDebugOnPages'] = "頁面調試資訊日誌";
$l['all']['LoggingDebugInfo'] = "調試資訊日誌";

$l['all']['PasswordHidden'] = "啟用密碼隱藏（將用星號顯示）";
$l['all']['TablesListing'] = "行/記錄每表格清單頁面";
$l['all']['TablesListingNum'] = "啟用表清單編號";
$l['all']['AjaxAutoComplete'] = "啟用Ajax自動完成";

$l['all']['RadiusServer'] = "Radius伺服器";
$l['all']['RadiusPort'] = "Radius埠";

$l['all']['UsernamePrefix'] = "使用者首碼";

$l['all']['batchName'] = "批量Id/名稱";
$l['all']['batchDescription'] = "批量描述";

$l['all']['NumberInstances'] = "創建數量";
$l['all']['UsernameLength'] = "用戶名字元數";
$l['all']['PasswordLength'] = "密碼字元數";

$l['all']['Expiration'] = "效期時間";
$l['all']['MaxAllSession'] = "最大總會話";
$l['all']['SessionTimeout'] = "會話超時";
$l['all']['IdleTimeout'] = "空閒超時";

$l['all']['DBEngine'] = "伺服器引擎";
$l['all']['radcheck'] = "radius檢查";
$l['all']['radreply'] = "radius回復";
$l['all']['radgroupcheck'] = "radius組檢查";
$l['all']['radgroupreply'] = "radius組回復";
$l['all']['usergroup'] = "用戶組";
$l['all']['radacct'] = "radius帳單";
$l['all']['operators'] = "操作人";
$l['all']['operators_acl'] = "操作人存取控制清單";
$l['all']['operators_acl_files'] = "操作人存取控制清單檔";
$l['all']['billingrates'] = "記帳費用";
$l['all']['hotspots'] = "熱點";
$l['all']['node'] = "節點";
$l['all']['nas'] = "nas";
$l['all']['hunt'] = "radius尋線群";
$l['all']['radpostauth'] = "radius提交認證";
$l['all']['radippool'] = "radiusIP位址集區";
$l['all']['userinfo'] = "使用者資訊";
$l['all']['dictionary'] = "字典";
$l['all']['realms'] = "域";
$l['all']['proxys'] = "代理";
$l['all']['billingpaypal'] = "PayPal記帳";
$l['all']['billingmerchant'] = "供貨方記帳";
$l['all']['billingplans'] = "記帳計畫";
$l['all']['billinghistory'] = "記帳歷史";
$l['all']['billinginfo'] = "記帳信息";


$l['all']['CreateIncrementingUsers'] = "創建增量用戶";
$l['all']['CreateRandomUsers'] = "創建隨機用戶";
$l['all']['StartingIndex'] = "開始索引";
$l['all']['EndingIndex'] = "結束索引";
$l['all']['RandomChars'] = "允許隨機字元";
$l['all']['Memfree'] = "空閒記憶體";
$l['all']['Uptime'] = "正常執行時間";
$l['all']['BandwidthUp'] = "上傳頻寬";
$l['all']['BandwidthDown'] = "下載頻寬";

$l['all']['BatchCost'] = "批量花費";

$l['all']['PaymentDate'] = "付款日";
$l['all']['PaymentStatus'] = "付款狀態";
$l['all']['FirstName'] = "名";
$l['all']['LastName'] = "姓";
$l['all']['VendorType'] = "設備類型";
$l['all']['PayerStatus'] = "付款人狀態";
$l['all']['PaymentAddressStatus'] = "付款位址狀態";
$l['all']['PayerEmail'] = "付款日Email";
$l['all']['TxnId'] = "交易ID";
$l['all']['PlanActive'] = "活動計畫";
$l['all']['PlanTimeType'] = "計畫時間類型";
$l['all']['PlanTimeBank'] = "計畫時間銀行";
$l['all']['PlanTimeRefillCost'] = "計畫補充花費";
$l['all']['PlanTrafficRefillCost'] = "計畫補充花費";
$l['all']['PlanBandwidthUp'] = "計畫上傳頻寬";
$l['all']['PlanBandwidthDown'] = "計畫下載頻寬";
$l['all']['PlanTrafficTotal'] = "計畫總流量";
$l['all']['PlanTrafficDown'] = "計畫下載流量";
$l['all']['PlanTrafficUp'] = "計畫上傳流量";
$l['all']['PlanRecurring'] = "計畫迴圈";
$l['all']['PlanRecurringPeriod'] = "計畫迴圈週期";
$l['all']['planRecurringBillingSchedule'] = "計畫重複記帳安排";
$l['all']['PlanCost'] = "計畫花費";
$l['all']['PlanSetupCost'] = "計畫安裝花費";
$l['all']['PlanTax'] = "計畫稅額";
$l['all']['PlanCurrency'] = "計畫貨幣";
$l['all']['PlanGroup'] = "計畫個人使用者配置（組）";
$l['all']['PlanType'] = "計畫類型";
$l['all']['PlanName'] = "計畫名稱";
$l['all']['PlanId'] = "計畫ID";

$l['all']['UserId'] = "用戶Id";

$l['all']['Invoice'] = "帳單";
$l['all']['InvoiceID'] = "帳單ID";
$l['all']['InvoiceItems'] = "帳單項目";
$l['all']['InvoiceStatus'] = "帳單狀態";

$l['all']['InvoiceType'] = "帳單類型";
$l['all']['Amount'] = "總額";
$l['all']['Total'] = "總計";
$l['all']['TotalInvoices'] = "總帳單";

$l['all']['PayTypeName'] = "付款類型名稱";
$l['all']['PayTypeNotes'] = "付款類型描述";
$l['all']['payment_type'] = "付款類型";
$l['all']['payments'] = "付款";
$l['all']['PaymentId'] = "付款ID";
$l['all']['PaymentInvoiceID'] = "帳單ID";
$l['all']['PaymentAmount'] = "支付金額";
$l['all']['PaymentDate'] = "日期";
$l['all']['PaymentType'] = "付款類型";
$l['all']['PaymentNotes'] = "付款備註";




$l['all']['Quantity'] = "總量";
$l['all']['ReceiverEmail'] = "接受電子郵件";
$l['all']['Business'] = "公司";
$l['all']['Tax'] = "稅額";
$l['all']['Cost'] = "花費";
$l['all']['TotalCost'] = "總花費";
$l['all']['TransactionFee'] = "交易費";
$l['all']['PaymentCurrency'] = "支付貨幣";
$l['all']['AddressRecipient'] = "位址接收人";
$l['all']['Street'] = "街道";
$l['all']['Country'] = "國家";
$l['all']['CountryCode'] = "國家代碼";
$l['all']['City'] = "城市";
$l['all']['State'] = "省份";
$l['all']['Zip'] = "郵編";

$l['all']['BusinessName'] = "公司名字";
$l['all']['BusinessPhone'] = "公司電話";
$l['all']['BusinessAddress'] = "公司地址";
$l['all']['BusinessWebsite'] = "公司網址";
$l['all']['BusinessEmail'] = "公司Email";
$l['all']['BusinessContactPerson'] = "公司連絡人";
$l['all']['DBPasswordEncryption'] = "資料庫密碼加密類型";


/***********************************************************************************
	工具提示
	輔助資訊輔助資訊,如為滑鼠懸停提示文本事件和彈出提示
 ************************************************************************************/

$l['Tooltip']['batchNameTooltip'] = "為本批創建提供一個識別字名稱";
$l['Tooltip']['batchDescriptionTooltip'] = "為本批創建提供一個一般描述";

$l['Tooltip']['hotspotTooltip'] = "選擇與這批實例相關聯的熱點名字";

$l['Tooltip']['startingIndexTooltip'] = "提供起始索引的創建使用者";
$l['Tooltip']['planTooltip'] = "選一個計畫來關聯用戶";

$l['Tooltip']['InvoiceEdit'] = "編輯帳單";
$l['Tooltip']['invoiceTypeTooltip'] = "帳單類型工具提示";
$l['Tooltip']['invoiceStatusTooltip'] = "帳單狀態工具提示";
$l['Tooltip']['invoiceID'] = "帳單ID類型";

$l['Tooltip']['amountTooltip'] = "金額工具提示";
$l['Tooltip']['taxTooltip'] = "稅額工具提示";

$l['Tooltip']['PayTypeName'] = "支付類型名稱";
$l['Tooltip']['EditPayType'] = "編輯支付類型";
$l['Tooltip']['RemovePayType'] = "移除支付類型";
$l['Tooltip']['paymentTypeTooltip'] = "付款類型友好的名稱,<br/>
                                        來描述付款的目的";
$l['Tooltip']['paymentTypeNotesTooltip'] = "描述付款類型的描述<br/>
                                        付款類型的操作";
$l['Tooltip']['EditPayment'] = "編輯付款";
$l['Tooltip']['PaymentId'] = "付款Id";
$l['Tooltip']['RemovePayment'] = "移除付款";
$l['Tooltip']['paymentInvoiceTooltip'] = "此次付款相關的帳單";



$l['Tooltip']['Username'] = "用戶名類型";
$l['Tooltip']['BatchName'] = "批量名稱類型";
$l['Tooltip']['UsernameWildcard'] = "提示: 你可以用字元 * 或 % 來制定一個萬用字元";
$l['Tooltip']['HotspotName'] = "熱點名稱類型";
$l['Tooltip']['NasName'] = "NAS名稱類型";
$l['Tooltip']['GroupName'] = "群組名稱類型";
$l['Tooltip']['AttributeName'] = "屬性名稱類型";
$l['Tooltip']['VendorName'] = "設備名稱類型";
$l['Tooltip']['PoolName'] = "IP位址集區名稱類型";
$l['Tooltip']['IPAddress'] = "IP位址集區類型";
$l['Tooltip']['Filter'] = "篩檢程式的類型，可以是任何字元的字串。用留空配對其它。";
$l['Tooltip']['Date'] = "日期類型 <br/> 示例: 1982-06-04 (Y-M-D)";
$l['Tooltip']['RateName'] = "價格名稱類型";
$l['Tooltip']['OperatorName'] = "操作人名稱類型";
$l['Tooltip']['BillingPlanName'] = "記帳計畫名稱類型";
$l['Tooltip']['PlanName'] = "計畫名稱類型";

$l['Tooltip']['EditRate'] = "編輯價格";
$l['Tooltip']['RemoveRate'] = "移除價格";

$l['Tooltip']['rateNameTooltip'] = "價格的名稱，<br/>
					來描述價格的用途";
$l['Tooltip']['rateTypeTooltip'] = "價格類型，來描述<br/>
					價格的操作";
$l['Tooltip']['rateCostTooltip'] = "價格花費金額";

$l['Tooltip']['planNameTooltip'] = "計畫的名字。這是<br/>
					一個友好的描述計畫的特性。";
$l['Tooltip']['planIdTooltip'] = "計畫ID提示工具";
$l['Tooltip']['planTimeTypeTooltip'] = "計畫時間類型提示工具";
$l['Tooltip']['planTimeBankTooltip'] = "計畫時間銀行提示工具";
$l['Tooltip']['planTimeRefillCostTooltip'] = "計畫時間補充話費提示工具";
$l['Tooltip']['planTrafficRefillCostTooltip'] = "計畫流量補充提示工具";
$l['Tooltip']['planBandwidthUpTooltip'] = "計畫上傳頻寬提示工具";
$l['Tooltip']['planBandwidthDownTooltip'] = "計畫下載頻寬提示工具";
$l['Tooltip']['planTrafficTotalTooltip'] = "計畫總流量提示工具";
$l['Tooltip']['planTrafficDownTooltip'] = "計畫下載流量提示工具";
$l['Tooltip']['planTrafficUpTooltip'] = "計畫流量上傳提示工具";

$l['Tooltip']['planRecurringTooltip'] = "計畫迴圈提示工具";
$l['Tooltip']['planRecurringBillingScheduleTooltip'] = "計畫迴圈記帳安排提示工具";
$l['Tooltip']['planRecurringPeriodTooltip'] = "計畫迴圈週期提示工具";
$l['Tooltip']['planCostTooltip'] = "計畫花費提示工具";
$l['Tooltip']['planSetupCostTooltip'] = "計畫安裝話費提示工具";
$l['Tooltip']['planTaxTooltip'] = "計畫稅額提示工具";
$l['Tooltip']['planCurrencyTooltip'] = "計畫貨幣提示工具";
$l['Tooltip']['planGroupTooltip'] = "計畫群組提示工具";

$l['Tooltip']['EditIPPool'] = "編輯IP位址集區";
$l['Tooltip']['RemoveIPPool'] = "移除IP位址集區";
$l['Tooltip']['EditIPAddress'] = "編輯IP地址";
$l['Tooltip']['RemoveIPAddress'] = "移除IP地址";

$l['Tooltip']['BusinessNameTooltip'] = "公司名稱提示工具";
$l['Tooltip']['BusinessPhoneTooltip'] = "公司電話提示工具";
$l['Tooltip']['BusinessAddressTooltip'] = "公司地址提示工具";
$l['Tooltip']['BusinessWebsiteTooltip'] = "公司網站提示工具";
$l['Tooltip']['BusinessEmailTooltip'] = "公司Email提示工具";
$l['Tooltip']['BusinessContactPersonTooltip'] = "公司連絡人提示工具";

$l['Tooltip']['proxyNameTooltip'] = "代理名稱";
$l['Tooltip']['proxyRetryDelayTooltip'] = "等待的時間(在短時間內)<br/>
					來自代理的響應, <br/>
					在重發代理請求之前";
$l['Tooltip']['proxyRetryCountTooltip'] = "發送重試次數 <br/>
					在放棄之前,並發送拒絕 <br/>
					消息給NAS.";
$l['Tooltip']['proxyDeadTimeTooltip'] = "如果主機不回應 <br/>
					給任意一個多重嘗試，<br/>
					然後FreeRADIUS將停止發送給它。<br/>
					代理請求，然後標記它‘廢棄’。";
$l['Tooltip']['proxyDefaultFallbackTooltip'] = "如果所有完全匹配的域 <br/>
						不響應，我們可以嘗試 <br/>
						";
$l['Tooltip']['realmNameTooltip'] = "功能變數名稱";
$l['Tooltip']['realmTypeTooltip'] = "設置默認radius";
$l['Tooltip']['realmSecretTooltip'] = "域RADIUS共用秘鑰安全";
$l['Tooltip']['realmAuthhostTooltip'] = "域認證主機";
$l['Tooltip']['realmAccthostTooltip'] = "域帳單主機";
$l['Tooltip']['realmLdflagTooltip'] = "允許負載平衡<br/>
					允許值為‘失效轉移’ <br/>
					和‘輪叫調度’。";
$l['Tooltip']['realmNostripTooltip'] = "不論是否去除 <br/>
					域尾碼";
$l['Tooltip']['realmHintsTooltip'] = "";
$l['Tooltip']['realmNotrealmTooltip'] = "";


$l['Tooltip']['vendorNameTooltip'] = "示例：cisco<br/>&nbsp;&nbsp;&nbsp;
                                        設備商名稱<br/>&nbsp;&nbsp;&nbsp;";
$l['Tooltip']['typeTooltip'] = "示例：string<br/>&nbsp;&nbsp;&nbsp;
                                        屬性變數類型<br/>&nbsp;&nbsp;&nbsp;
					(string, integer, date, ipaddr).";
$l['Tooltip']['attributeTooltip'] = "示例：Framed-IPAddress<br/>&nbsp;&nbsp;&nbsp;
                                        準確的屬性名稱<br/>&nbsp;&nbsp;&nbsp;";

$l['Tooltip']['RecommendedOPTooltip'] = "示例：:=<br/>&nbsp;&nbsp;&nbsp;
                                        推薦的屬性的操作符<br/>&nbsp;&nbsp;&nbsp;
					(one of: := == != etc...)";
$l['Tooltip']['RecommendedTableTooltip'] = "示例：check<br/>&nbsp;&nbsp;&nbsp;
                                        推薦的目標表<br/>&nbsp;&nbsp;&nbsp;
					(either check or reply).";
$l['Tooltip']['RecommendedTooltipTooltip'] = "示例：用戶的ip地址<br/>&nbsp;&nbsp;&nbsp;
                                        推薦的工具提示<br/>&nbsp;&nbsp;&nbsp;";
$l['Tooltip']['RecommendedHelperTooltip'] = "添加屬性為<br/>&nbsp;&nbsp;&nbsp;
                                        可使用的説明函數<br/>&nbsp;&nbsp;&nbsp;";



$l['Tooltip']['AttributeEdit'] = "編輯屬性";

$l['Tooltip']['BatchDetails'] = "批量詳情";

$l['Tooltip']['UserEdit'] = "編輯用戶";
$l['Tooltip']['HotspotEdit'] = "編輯熱點";
$l['Tooltip']['EditNAS'] = "編輯NAS";
$l['Tooltip']['RemoveNAS'] = "移除NAS";
$l['Tooltip']['EditHG'] = "編輯尋線群";
$l['Tooltip']['RemoveHG'] = "移除尋線群";
$l['Tooltip']['hgNasIpAddress'] = "輸入主機/IP位址";
$l['Tooltip']['hgGroupName'] = "輸入NAS組名稱";
$l['Tooltip']['hgNasPortId'] = "輸入NAS埠Id";
$l['Tooltip']['EditUserGroup'] = "編輯用戶組";
$l['Tooltip']['ListUserGroups'] = "用戶組列表";
$l['Tooltip']['DeleteUserGroup'] = "刪除關聯用戶組";

$l['Tooltip']['EditProfile'] = "編輯個人設定檔";

$l['Tooltip']['EditRealm'] = "編輯域";
$l['Tooltip']['EditProxy'] = "編輯代理";

$l['Tooltip']['EditGroup'] = "編輯組";

$l['FormField']['mngradgroupcheck.php']['ToolTip']['Value'] = "如果指定的值，然後只有單一的記錄都同組名稱和指定值匹配，指定值將被移除。如果省略了值，然後所有那些特定組名稱的記錄將被移除！";

$l['FormField']['mngradgroupreplydel.php']['ToolTip']['Value'] = "如果指定的值，然後只有單一的記錄都同組名稱和指定值匹配，指定值將被移除。如果省略了值，然後所有那些特定組名稱的記錄將被移除！";

$l['FormField']['mngradnasnew.php']['ToolTip']['NasShortname'] = "（描述名稱）";

$l['FormField']['mngradusergroupdel.php']['ToolTip']['Groupname'] = "如果指定的組，然後只有單一的記錄都同用戶名和組匹配，指定的將被移除。如果省略了組，然後所有那些特定的用戶名稱記錄將被移除！";


$l['Tooltip']['usernameTooltip'] = "準確的用戶名，用戶將<br/>&nbsp;&nbsp;&nbsp;
					用來連接系統";
$l['Tooltip']['passwordTypeTooltip'] = "The password type used to authenticate the user in Radius.";					
$l['Tooltip']['passwordTooltip'] = "密碼實例包含在系統裡<br/>&nbsp;&nbsp;&nbsp;
					所以要格外小心";
$l['Tooltip']['groupTooltip'] = "用戶將被添加到這個組<br/>&nbsp;&nbsp;&nbsp;
					通過分配一個用戶特定組<br/>&nbsp;&nbsp;&nbsp;
					使用者必須受制於組的屬性";
$l['Tooltip']['macaddressTooltip'] = "示例：00:AA:BB:CC:DD:EE<br/>&nbsp;&nbsp;&nbsp;
					MAC位址格式應該是相同的<br/>&nbsp;&nbsp;&nbsp;
					隨著NAS發送它，通常這<br/>&nbsp;&nbsp;&nbsp;
					沒有字元";
$l['Tooltip']['pincodeTooltip'] = "示例：khrivnxufi101<br/>&nbsp;&nbsp;&nbsp;
					這是準確的pin碼將作為用戶進入它<br/>&nbsp;&nbsp;&nbsp;
					你可以使用alpha數位字元";
$l['Tooltip']['usernamePrefixTooltip'] = "示例：TMP_ POP_ WIFI1_ <br/>&nbsp;&nbsp;&nbsp;
					這個用戶名首碼會增加<br/>&nbsp;&nbsp;&nbsp;
					生成的用戶名最終。";
$l['Tooltip']['instancesToCreateTooltip'] = "示例：100<br/>&nbsp;&nbsp;&nbsp;
					用戶創建隨機的數量<br/>&nbsp;&nbsp;&nbsp;
					用指定的個人設定檔";
$l['Tooltip']['lengthOfUsernameTooltip'] = "示例：8<br/>&nbsp;&nbsp;&nbsp;
					用戶名的字元長度<br/>&nbsp;&nbsp;&nbsp;
					被創建。建議8-12個字元。";
$l['Tooltip']['lengthOfPasswordTooltip'] = "示例：8<br/>&nbsp;&nbsp;&nbsp;
					密碼的字元長度<br/>&nbsp;&nbsp;&nbsp;
					被創建。建議8-12個字元。";


$l['Tooltip']['hotspotNameTooltip'] = "Example：酒店的電吉他<br/>&nbsp;&nbsp;&nbsp;
					一個友好的熱點名稱<br/>";

$l['Tooltip']['hotspotMacaddressTooltip'] = "示例：00-aa-bb-cc-dd-ee<br/>&nbsp;&nbsp;&nbsp;
					NAS的MAC地址<br/>";

$l['Tooltip']['geocodeTooltip'] = "示例：-1.002,-2.201<br/>&nbsp;&nbsp;&nbsp;
					GooleMaps位置代碼<br/>&nbsp;&nbsp;&nbsp;
					來PIN熱點/NAS在上（看GIS）";

$l['Tooltip']['reassignplanprofiles'] = "如果開啟,當應用使用者資訊 <br/>
					這個個人設定檔中顯示的個人設定檔選項卡將被忽略和<br/>
					個人設定檔將被重新分配根據計畫個人設定檔關聯";

/* ********************************************************************************** */




/* **********************************************************************************
連結和按鈕
 ************************************************************************************/

$l['button']['DashboardSettings'] = "儀錶盤設置";


$l['button']['GenerateReport'] = "生成報告";

$l['button']['ListPayTypes'] = "顯示付款類型";
$l['button']['NewPayType'] = "新建付款類型";
$l['button']['EditPayType'] = "編輯付款類型";
$l['button']['RemovePayType'] = "移除付款類型";
$l['button']['ListPayments'] = "顯示支付";
$l['button']['NewPayment'] = "新建支付";
$l['button']['EditPayment'] = "編輯支付";
$l['button']['RemovePayment'] = "移除支付";

$l['button']['NewUsers'] = "新建用戶";

$l['button']['ClearSessions'] = "清除會話";
$l['button']['Dashboard'] = "儀錶盤";
$l['button']['MailSettings'] = "郵件設置";

$l['button']['Batch'] = "批量";
$l['button']['BatchHistory'] = "批量歷史";
$l['button']['BatchDetails'] = "批量明細";

$l['button']['ListRates'] = "顯示率列";
$l['button']['NewRate'] = "新建率列";
$l['button']['EditRate'] = "編輯率列";
$l['button']['RemoveRate'] = "移除率列";

$l['button']['ListPlans'] = "顯示計畫";
$l['button']['NewPlan'] = "新建計畫";
$l['button']['EditPlan'] = "編輯計畫";
$l['button']['RemovePlan'] = "移除計畫";

$l['button']['ListInvoices'] = "顯示帳單";
$l['button']['NewInvoice'] = "新建帳單";
$l['button']['EditInvoice'] = "編輯帳單";
$l['button']['RemoveInvoice'] = "移除帳單";

$l['button']['ListRealms'] = "顯示域";
$l['button']['NewRealm'] = "新建域";
$l['button']['EditRealm'] = "編輯域";
$l['button']['RemoveRealm'] = "移除域";

$l['button']['ListProxys'] = "顯示代理";
$l['button']['NewProxy'] = "新建代理";
$l['button']['EditProxy'] = "編輯代理";
$l['button']['RemoveProxy'] = "移除代理";

$l['button']['ListAttributesforVendor'] = "顯示屬性";
$l['button']['NewVendorAttribute'] = "新建屬性";
$l['button']['EditVendorAttribute'] = "編輯屬性";
$l['button']['SearchVendorAttribute'] = "搜索屬性";
$l['button']['RemoveVendorAttribute'] = "移除屬性";
$l['button']['ImportVendorDictionary'] = "導入字典/屬性";


$l['button']['BetweenDates'] = "始末日期";
$l['button']['Where'] = "條件";
$l['button']['AccountingFieldsinQuery'] = "查詢帳單域";
$l['button']['OrderBy'] = "排序";
$l['button']['HotspotAccounting'] = "熱點帳單";
$l['button']['HotspotsComparison'] = "熱點比較";

$l['button']['CleanupStaleSessions'] = "清理過期帳單";
$l['button']['DeleteAccountingRecords'] = "刪除帳單記錄";

$l['button']['ListUsers'] = "用戶列表";
$l['button']['ListBatches'] = "顯示批量";
$l['button']['RemoveBatch'] = "移除批量";
$l['button']['ImportUsers'] = "導入用戶";
$l['button']['NewUser'] = "新建用戶";
$l['button']['NewUserQuick'] = "添加用戶";
$l['button']['BatchAddUsers'] = "批量添加用戶";
$l['button']['EditUser'] = "編輯用戶";
$l['button']['SearchUsers'] = "搜索用戶";
$l['button']['RemoveUsers'] = "移除用戶";
$l['button']['ListHotspots'] = "顯示熱點";
$l['button']['NewHotspot'] = "新建熱點";
$l['button']['EditHotspot'] = "編輯熱點";
$l['button']['RemoveHotspot'] = "移除熱點";

$l['button']['ListIPPools'] = "顯示IP位址集區";
$l['button']['NewIPPool'] = "新建IP位址集區";
$l['button']['EditIPPool'] = "編輯IP位址集區";
$l['button']['RemoveIPPool'] = "移除IP位址集區";

$l['button']['ListNAS'] = "顯示NAS";
$l['button']['NewNAS'] = "新建NAS";
$l['button']['EditNAS'] = "編輯NAS";
$l['button']['RemoveNAS'] = "移除NAS";
$l['button']['ListHG'] = "顯示尋線群";
$l['button']['NewHG'] = "新建尋線群";
$l['button']['EditHG'] = "編輯尋線群";
$l['button']['RemoveHG'] = "移除尋線群";
$l['button']['ListUserGroup'] = "顯示使用者組";
$l['button']['ListUsersGroup'] = "顯示使用者組";
$l['button']['NewUserGroup'] = "新建用戶組";
$l['button']['EditUserGroup'] = "編輯用戶組";
$l['button']['RemoveUserGroup'] = "移除用戶組";

$l['button']['ListProfiles'] = "設定檔列表";
$l['button']['NewProfile'] = "新建設定檔";
$l['button']['EditProfile'] = "編輯設定檔";
$l['button']['DuplicateProfile'] = "複製設定檔";
$l['button']['RemoveProfile'] = "刪除設定檔";

$l['button']['ListGroupReply'] = "顯示組回復";
$l['button']['SearchGroupReply'] = "搜索組回復";
$l['button']['NewGroupReply'] = "新建組回復";
$l['button']['EditGroupReply'] = "編輯組回復";
$l['button']['RemoveGroupReply'] = "移除組回復";

$l['button']['ListGroupCheck'] = "顯示組檢查";
$l['button']['SearchGroupCheck'] = "搜索組檢查";
$l['button']['NewGroupCheck'] = "新建組檢查";
$l['button']['EditGroupCheck'] = "編輯組檢查";
$l['button']['RemoveGroupCheck'] = "移除組檢查";

$l['button']['UserAccounting'] = "用戶帳單";
$l['button']['IPAccounting'] = "IP帳單";
$l['button']['NASIPAccounting'] = "NAS IP帳單";
$l['button']['NASIPAccountingOnlyActive'] = "只顯示活動";
$l['button']['DateAccounting'] = "日期帳單";
$l['button']['AllRecords'] = "所有記錄";
$l['button']['ActiveRecords'] = "活動記錄";

$l['button']['PlanUsage'] = "計畫使用";

$l['button']['OnlineUsers'] = "線上用戶";
$l['button']['LastConnectionAttempts'] = "連接記錄";
$l['button']['TopUser'] = "用戶排行";
$l['button']['History'] = "歷史";

$l['button']['ServerStatus'] = "伺服器狀態";
$l['button']['ServicesStatus'] = "服務狀態";

$l['button']['daloRADIUSLog'] = "daloRADIUS日誌";
$l['button']['RadiusLog'] = "Radius日誌";
$l['button']['SystemLog'] = "系統日誌";
$l['button']['BootLog'] = "引導日誌";

$l['button']['UserLogins'] = "用戶登錄";
$l['button']['UserDownloads'] = "用戶下載";
$l['button']['UserUploads'] = "用戶上傳";
$l['button']['TotalLogins'] = "總登錄";
$l['button']['TotalTraffic'] = "總流量";
$l['button']['LoggedUsers'] = "使用者日誌";

$l['button']['ViewMAP'] = "顯示地圖";
$l['button']['EditMAP'] = "編輯地圖";
$l['button']['RegisterGoogleMapsAPI'] = "註冊穀歌地圖API";

$l['button']['UserSettings'] = "用戶設置";
$l['button']['DatabaseSettings'] = "資料庫設置";
$l['button']['LanguageSettings'] = "語言設置";
$l['button']['LoggingSettings'] = "日誌設置";
$l['button']['InterfaceSettings'] = "介面設置";

$l['button']['ReAssignPlanProfiles'] = "重新分配計畫個人設定檔";

$l['button']['TestUserConnectivity'] = "測試用戶連通性";
$l['button']['DisconnectUser'] = "斷開用戶";

$l['button']['ManageBackups'] = "管理備份";
$l['button']['CreateBackups'] = "創建備份";

$l['button']['ListOperators'] = "顯示操作人";
$l['button']['NewOperator'] = "新建操作人";
$l['button']['EditOperator'] = "編輯操作人";
$l['button']['RemoveOperator'] = "移除操作人";

$l['button']['ProcessQuery'] = "查詢進程";

 
 
/*********************************************************************************** */


/***********************************************************************************
標題
在題注中文本相關的所有標題，表和指定佈局文本
************************************************************************************/

$l['title']['ImportUsers'] = "導入用戶";


/*$l['title']['Dashboard'] = "儀錶盤";*/

$l['title']['Dashboard'] = "控制台";
$l['title']['DashboardAlerts'] = "警告";

$l['title']['Invoice'] = "帳單";
$l['title']['Invoices'] = "帳單";
$l['title']['InvoiceRemoval'] = "帳單移除";
$l['title']['Payments'] = "支付";
$l['title']['Items'] = "項目";

$l['title']['PayTypeInfo'] = "支付類型資訊";
$l['title']['PaymentInfo'] = "支付資訊";

 
$l['title']['RateInfo'] = "價格資訊";
$l['title']['PlanInfo'] = "計畫資訊";
$l['title']['TimeSettings'] = "時間設置";
$l['title']['BandwidthSettings'] = "頻寬設置";
$l['title']['PlanRemoval'] = "計畫移除";

$l['title']['BatchRemoval'] = "批量移除";

$l['title']['Backups'] = "備份";
$l['title']['FreeRADIUSTables'] = "FreeRADIUS表";
$l['title']['daloRADIUSTables'] = "daloRADIUS表";

$l['title']['IPPoolInfo'] = "IP位址集區信息";

$l['title']['BusinessInfo'] = "公司資訊";

$l['title']['CleanupRecords'] = "清除記錄";
$l['title']['DeleteRecords'] = "刪除記錄";

$l['title']['RealmInfo'] = "域信息";

$l['title']['ProxyInfo'] = "代理資訊";

$l['title']['VendorAttribute'] = "設備屬性";

$l['title']['AccountRemoval'] = "帳單移除";
$l['title']['AccountInfo'] = "帳單信息";

$l['title']['Profiles'] = "個人配置";
$l['title']['ProfileInfo'] = "個人配置資訊";

$l['title']['GroupInfo'] = "組信息";
$l['title']['GroupAttributes'] = "組屬性";

$l['title']['NASInfo'] = "NAS信息";
$l['title']['NASAdvanced'] = "NAS高級";
$l['title']['HGInfo'] = "尋線群信息";
$l['title']['UserInfo'] = "使用者資訊";
$l['title']['BillingInfo'] = "記帳信息";

$l['title']['Attributes'] = "屬性";
$l['title']['ProfileAttributes'] = "個人配置屬性";

$l['title']['HotspotInfo'] = "熱點資訊";
$l['title']['HotspotRemoval'] = "熱點移除";

$l['title']['ContactInfo'] = "聯繫資訊";

$l['title']['Plan'] = "計畫";

$l['title']['Profile'] = "個人配置";
$l['title']['Groups'] = "組";
$l['title']['RADIUSCheck'] = "檢查屬性";
$l['title']['RADIUSReply'] = "回復屬性";

$l['title']['Settings'] = "設置";
$l['title']['DatabaseSettings'] = "資料庫設置";
$l['title']['DatabaseTables'] = "資料庫表";
$l['title']['AdvancedSettings'] = "高級設置";

$l['title']['Advanced'] = "高級";
$l['title']['Optional'] = "可選";

/* ********************************************************************************** */

/* **********************************************************************************
圖表
一般圖表文本
 ************************************************************************************/
$l['graphs']['Day'] = "日";
$l['graphs']['Month'] = "月";
$l['graphs']['Year'] = "年";
$l['graphs']['Jan'] = "一月";
$l['graphs']['Feb'] = "二月";
$l['graphs']['Mar'] = "三月";
$l['graphs']['Apr'] = "四月";
$l['graphs']['May'] = "五月";
$l['graphs']['Jun'] = "六月";
$l['graphs']['Jul'] = "七月";
$l['graphs']['Aug'] = "八月";
$l['graphs']['Sep'] = "九月";
$l['graphs']['Oct'] = "十月";
$l['graphs']['Nov'] = "十一月";
$l['graphs']['Dec'] = "十二月";


/* ********************************************************************************** */

/* **********************************************************************************
文本
會在頁面使用的一般的文本資訊
 ************************************************************************************/

$l['text']['LoginRequired'] = "需要登錄";
$l['text']['LoginPlease'] = "請先登錄";

/* ********************************************************************************** */



/* **********************************************************************************
聯繫資訊
相關的所有聯繫資訊文本、使用者資訊、熱點所有者聯繫資訊等
 ************************************************************************************/

$l['ContactInfo']['FirstName'] = "名";
$l['ContactInfo']['LastName'] = "姓";
$l['ContactInfo']['Email'] = "電子郵件";
$l['ContactInfo']['Department'] = "部門";
$l['ContactInfo']['WorkPhone'] = "工作電話";
$l['ContactInfo']['HomePhone'] = "家庭電話";
$l['ContactInfo']['Phone'] = "電話";
$l['ContactInfo']['MobilePhone'] = "手機";
$l['ContactInfo']['Notes'] = "備註";
$l['ContactInfo']['EnableUserUpdate'] = "允許用戶更新";
$l['ContactInfo']['EnablePortalLogin'] = "允許用戶登錄門戶";
$l['ContactInfo']['PortalLoginPassword'] = "設置登錄密碼";

$l['ContactInfo']['OwnerName'] = "所有者姓名";
$l['ContactInfo']['OwnerEmail'] = "所有者電子郵件";
$l['ContactInfo']['ManagerName'] = "管理員姓名";
$l['ContactInfo']['ManagerEmail'] = "管理員電子郵件";
$l['ContactInfo']['Company'] = "公司";
$l['ContactInfo']['Address'] = "地址";
$l['ContactInfo']['City'] = "城市";
$l['ContactInfo']['State'] = "省份";
$l['ContactInfo']['Country'] = "國家";
$l['ContactInfo']['Zip'] = "郵編";
$l['ContactInfo']['Phone1'] = "電話1";
$l['ContactInfo']['Phone2'] = "電話2";
$l['ContactInfo']['HotspotType'] = "熱點類型";
$l['ContactInfo']['CompanyWebsite'] = "公司網站";
$l['ContactInfo']['CompanyPhone'] = "公司電話";
$l['ContactInfo']['CompanyEmail'] = "公司電子郵件";
$l['ContactInfo']['CompanyContact'] = "聯繫公司";

$l['ContactInfo']['PlanName'] = "計畫名稱";
$l['ContactInfo']['ContactPerson'] = "連絡人";
$l['ContactInfo']['PaymentMethod'] = "支付方式";
$l['ContactInfo']['Cash'] = "現金";
$l['ContactInfo']['CreditCardNumber'] = "信用卡卡號";
$l['ContactInfo']['CreditCardName'] = "信用卡名稱";
$l['ContactInfo']['CreditCardVerificationNumber'] = "信用卡驗證碼";
$l['ContactInfo']['CreditCardType'] = "信用卡類型";
$l['ContactInfo']['CreditCardExpiration'] = "信用卡有效期";

/* ********************************************************************************** */

$l['Intro']['configdashboard.php'] = "儀錶盤設置";



$l['Intro']['paymenttypesmain.php'] = "支付類型頁面";
$l['Intro']['paymenttypesdel.php'] = "刪除支付類型條目";
$l['Intro']['paymenttypesedit.php'] = "編輯支付類型明細";
$l['Intro']['paymenttypeslist.php'] = "支付類型表格";
$l['Intro']['paymenttypesnew.php'] = "新建支付類型條目";
$l['Intro']['paymenttypeslist.php'] = "支付類型表格";
$l['Intro']['paymentslist.php'] = "支付表格";
$l['Intro']['paymentsmain.php'] = "支付頁面";
$l['Intro']['paymentsdel.php'] = "刪除支付條目";
$l['Intro']['paymentsedit.php'] = "編輯支付明細";
$l['Intro']['paymentsnew.php'] = "新建支付條目";

$l['Intro']['billhistorymain.php'] = "記帳歷史";
$l['Intro']['msgerrorpermissions.php'] = "錯誤";

$l['Intro']['repnewusers.php'] = "顯示新使用者";

$l['Intro']['mngradproxys.php'] = "管理代理";
$l['Intro']['mngradproxysnew.php'] = "新建代理";
$l['Intro']['mngradproxyslist.php'] = "顯示代理";
$l['Intro']['mngradproxysedit.php'] = "編輯代理";
$l['Intro']['mngradproxysdel.php'] = "移除代理";

$l['Intro']['mngradrealms.php'] = "管理域";
$l['Intro']['mngradrealmsnew.php'] = "新建域";
$l['Intro']['mngradrealmslist.php'] = "顯示域";
$l['Intro']['mngradrealmsedit.php'] = "編輯域";
$l['Intro']['mngradrealmsdel.php'] = "移除域";

$l['Intro']['mngradattributes.php'] = "設備屬性管理";
$l['Intro']['mngradattributeslist.php'] = "設備的屬性清單";
$l['Intro']['mngradattributesnew.php'] = "新建設備屬性";
$l['Intro']['mngradattributesedit.php'] = "編輯設備屬性";
$l['Intro']['mngradattributessearch.php'] = "搜索屬性";
$l['Intro']['mngradattributesdel.php'] = "移除設備屬性";
$l['Intro']['mngradattributesimport.php'] = "導入設備字典";
$l['Intro']['mngimportusers.php'] = "導入用戶";


$l['Intro']['acctactive.php'] = "活動記錄帳單";
$l['Intro']['acctall.php'] = "所有用戶帳單";
$l['Intro']['acctdate.php'] = "日期方式帳單";
$l['Intro']['accthotspot.php'] = "熱點帳單";
$l['Intro']['acctipaddress.php'] = "IP帳單";
$l['Intro']['accthotspotcompare.php'] = "熱點比較";
$l['Intro']['acctmain.php'] = "帳單頁面";
$l['Intro']['acctplans.php'] = "計畫帳單頁面";
$l['Intro']['acctnasipaddress.php'] = "NAS IP帳單";
$l['Intro']['acctusername.php'] = "用戶帳單";
$l['Intro']['acctcustom.php'] = "客戶帳單";
$l['Intro']['acctcustomquery.php'] = "客戶查詢帳單";
$l['Intro']['acctmaintenance.php'] = "帳單記錄維護";
$l['Intro']['acctmaintenancecleanup.php'] = "刪除過期帳單";
$l['Intro']['acctmaintenancedelete.php'] = "刪除帳單記錄";

$l['Intro']['billmain.php'] = "記帳頁面";
$l['Intro']['ratesmain.php'] = "價格記帳頁面";
$l['Intro']['billratesdate.php'] = "價格預付帳單";
$l['Intro']['billratesdel.php'] = "移除利率條目";
$l['Intro']['billratesedit.php'] = "編輯利率資訊";
$l['Intro']['billrateslist.php'] = "帳單利率表";
$l['Intro']['billratesnew.php'] = "新建利率列表";

$l['Intro']['paypalmain.php'] = "PayPal交易頁面";
$l['Intro']['billpaypaltransactions.php'] = "PayPal交易頁面";

$l['Intro']['billhistoryquery.php'] = "記帳歷史";

$l['Intro']['billinvoice.php'] = "會計帳單";
$l['Intro']['billinvoicedel.php'] = "刪除帳單條目";
$l['Intro']['billinvoiceedit.php'] = "編輯帳單";
$l['Intro']['billinvoicelist.php'] = "顯示帳單";
$l['Intro']['billinvoicereport.php'] = "帳單報告";
$l['Intro']['billinvoicenew.php'] = "新建帳單";

$l['Intro']['billplans.php'] = "記帳計畫頁面";
$l['Intro']['billplansdel.php'] = "刪除計畫條目";
$l['Intro']['billplansedit.php'] = "編輯計畫明細";
$l['Intro']['billplanslist.php'] = "計畫表";
$l['Intro']['billplansnew.php'] = "新建計畫條目";

$l['Intro']['billpos.php'] = "銷售頁面的記帳點";
$l['Intro']['billposdel.php'] = "刪除用戶";
$l['Intro']['billposedit.php'] = "編輯用戶";
$l['Intro']['billposlist.php'] = "顯示使用者";
$l['Intro']['billposnew.php'] = "新建用戶";

$l['Intro']['giseditmap.php'] = "編輯地圖模式";
$l['Intro']['gismain.php'] = "GIS繪圖";
$l['Intro']['gisviewmap.php'] = "V查看地圖模式";

$l['Intro']['graphmain.php'] = "使用圖表";
$l['Intro']['graphsalltimetrafficcompare.php'] = "總流量使用比較";
$l['Intro']['graphsalltimelogins.php'] = "總登錄";
$l['Intro']['graphsloggedusers.php'] = "已登錄用戶";
$l['Intro']['graphsoveralldownload.php'] = "用戶下載";
$l['Intro']['graphsoveralllogins.php'] = "用戶登錄";
$l['Intro']['graphsoverallupload.php'] = "用戶上傳";

$l['Intro']['rephistory.php'] = "活動歷史";
$l['Intro']['replastconnect.php'] = "最後嘗試連接";
$l['Intro']['repstatradius.php'] = "守護進程資訊";
$l['Intro']['repstatserver.php'] = "伺服器狀態和資訊";
$l['Intro']['reponline.php'] = "顯示線上使用者";
$l['Intro']['replogssystem.php'] = "系統日誌檔";
$l['Intro']['replogsradius.php'] = "RADIUS伺服器日誌檔";
$l['Intro']['replogsdaloradius.php'] = "daloRADIUS日誌檔";
$l['Intro']['replogsboot.php'] = "Boot日誌檔";
$l['Intro']['replogs.php'] = "日誌";
$l['Intro']['rephb.php'] = "心跳";
$l['Intro']['rephbdashboard.php'] = "daloRADIUS NAS儀錶盤";
$l['Intro']['repbatch.php'] = "批量";
$l['Intro']['mngbatchlist.php'] = "批量會話列表";
$l['Intro']['repbatchlist.php'] = "批量用戶列表";
$l['Intro']['repbatchdetails.php'] = "批量明細";

$l['Intro']['rephsall.php'] = "熱點列表";
$l['Intro']['repmain.php'] = "報告頁面";
$l['Intro']['repstatus.php'] = "狀態頁面";
$l['Intro']['reptopusers.php'] = "用戶使用詳情";
$l['Intro']['repusername.php'] = "用戶列表";

$l['Intro']['mngbatch.php'] = "創建批量用戶";
$l['Intro']['mngbatchdel.php'] = "刪除批量會話";

$l['Intro']['mngdel.php'] = "移除用戶";
$l['Intro']['mngedit.php'] = "編輯用戶明細";
$l['Intro']['mnglistall.php'] = "用戶列表";
$l['Intro']['mngmain.php'] = "用戶和熱點管理";
$l['Intro']['mngbatch.php'] = "批量用戶管理";
$l['Intro']['mngnew.php'] = "新建用戶";
$l['Intro']['mngnewquick.php'] = "快速添加用戶";
$l['Intro']['mngsearch.php'] = "搜索用戶";

$l['Intro']['mnghsdel.php'] = "移除熱點";
$l['Intro']['mnghsedit.php'] = "編輯熱點明細";
$l['Intro']['mnghslist.php'] = "顯示熱點";
$l['Intro']['mnghsnew.php'] = "新建熱點";

$l['Intro']['mngradusergroupdel.php'] = "移除用戶組繪圖";
$l['Intro']['mngradusergroup.php'] = "使用者組配置";
$l['Intro']['mngradusergroupnew.php'] = "新建用戶組繪圖";
$l['Intro']['mngradusergrouplist'] = "資料庫使用者組繪圖";
$l['Intro']['mngradusergrouplistuser'] = "資料庫使用者組繪圖";
$l['Intro']['mngradusergroupedit'] = "編輯用戶組繪圖";

$l['Intro']['mngradippool.php'] = "IP位址集區配置";
$l['Intro']['mngradippoolnew.php'] = "新建IP位址集區";
$l['Intro']['mngradippoollist.php'] = "顯示IP位址集區";
$l['Intro']['mngradippooledit.php'] = "編輯IP位址集區";
$l['Intro']['mngradippooldel.php'] = "移除IP位址集區";

$l['Intro']['mngradnas.php'] = "NAS配置";
$l['Intro']['mngradnasnew.php'] = "新建NAS記錄";
$l['Intro']['mngradnaslist.php'] = "NAS資料庫清單";
$l['Intro']['mngradnasedit.php'] = "編輯NAS記錄";
$l['Intro']['mngradnasdel.php'] = "移除NAS記錄";

$l['Intro']['mngradhunt.php'] = "尋線群配置";
$l['Intro']['mngradhuntnew.php'] = "新建尋線群記錄";
$l['Intro']['mngradhuntlist.php'] = "資料庫尋線群清單";
$l['Intro']['mngradhuntedit.php'] = "編輯尋線群記錄";
$l['Intro']['mngradhuntdel.php'] = "移除尋線群記錄";

$l['Intro']['mngradprofiles.php'] = "設定檔列表";
$l['Intro']['mngradprofilesedit.php'] = "編輯組配置";
$l['Intro']['mngradprofilesduplicate.php'] = "複製組配置";
$l['Intro']['mngradprofilesdel.php'] = "刪除組配置";
$l['Intro']['mngradprofileslist.php'] = "顯示組配置";
$l['Intro']['mngradprofilesnew.php'] = "新建組配置";

$l['Intro']['mngradgroups.php'] = "配置組";

$l['Intro']['mngradgroupreplynew.php'] = "新建組回復繪圖";
$l['Intro']['mngradgroupreplylist.php'] = "資料庫組回復繪圖";
$l['Intro']['mngradgroupreplyedit.php'] = "編輯組回復繪圖";
$l['Intro']['mngradgroupreplydel.php'] = "移除組回復繪圖";
$l['Intro']['mngradgroupreplysearch.php'] = "搜索組回復繪圖";

$l['Intro']['mngradgroupchecknew.php'] = "新建組檢查繪圖";
$l['Intro']['mngradgroupchecklist.php'] = "資料庫組檢查繪圖";
$l['Intro']['mngradgroupcheckedit.php'] = "編輯組檢查繪圖";
$l['Intro']['mngradgroupcheckdel.php'] = "移除組檢查繪圖";
$l['Intro']['mngradgroupchecksearch.php'] = "搜索組檢查繪圖";

$l['Intro']['configuser.php'] = "配置使用者";
$l['Intro']['configmail.php'] = "配置郵件";

$l['Intro']['configdb.php'] = "配置資料庫";
$l['Intro']['configlang.php'] = "配置語言";
$l['Intro']['configlogging.php'] = "配置日誌";
$l['Intro']['configinterface.php'] = "配置Web介面";
$l['Intro']['configmainttestuser.php'] = "測試用戶連通性";
$l['Intro']['configmain.php'] = "配置資料庫";
$l['Intro']['configmaint.php'] = "維護";
$l['Intro']['configmaintdisconnectuser.php'] = "斷開用戶";
$l['Intro']['configbusiness.php'] = "公司明細";
$l['Intro']['configbusinessinfo.php'] = "公司資訊";
$l['Intro']['configbackup.php'] = "備份";
$l['Intro']['configbackupcreatebackups.php'] = "創建備份";
$l['Intro']['configbackupmanagebackups.php'] = "管理備份";

$l['Intro']['configoperators.php'] = "配置操作人";
$l['Intro']['configoperatorsdel.php'] = "移除操作人";
$l['Intro']['configoperatorsedit.php'] = "編輯操作人設置";
$l['Intro']['configoperatorsnew.php'] = "新建操作人";
$l['Intro']['configoperatorslist.php'] = "操作人列表";

$l['Intro']['login.php'] = "登錄";

$l['captions']['providebillratetodel'] = "提供你想去除的價格類型條目";
$l['captions']['detailsofnewrate'] = "可以填充下面新建價格的明細";
$l['captions']['filldetailsofnewrate'] = "填充下面新建價格條目的明細";

/* **********************************************************************************
 * 説明頁面資訊
 *每個頁面都有一個標題是前奏類的標題，當點擊
 *它會顯示/隱藏helpPage格的內容是具體的描述
 *頁，基本上你的擴展工具提示。
 ************************************************************************************/

$l['helpPage']['configdashboard'] = "控制台設置";


$l['helpPage']['repnewusers'] = "下拉表顯示了每個月創建的新用戶.";

$l['helpPage']['login'] = "";

$l['helpPage']['billpaypaltransactions'] = "顯示所有支付寶交易";
$l['helpPage']['billhistoryquery'] = "顯示所有使用者計費歷史(年代)";

$l['helpPage']['billinvoicereport'] = "";

$l['helpPage']['billinvoicelist'] = "";
$l['helpPage']['billinvoicenew'] = "";
$l['helpPage']['billinvoiceedit'] = "";
$l['helpPage']['billinvoicedel'] = "";

$l['helpPage']['paymenttypeslist'] = "";
$l['helpPage']['paymenttypesnew'] = "";
$l['helpPage']['paymenttypesedit'] = "";
$l['helpPage']['paymenttypesdel'] = "";
$l['helpPage']['paymenttypesdate'] = "";

$l['helpPage']['paymentslist'] = "";
$l['helpPage']['paymentsnew'] = "";
$l['helpPage']['paymentsedit'] = "";
$l['helpPage']['paymentsdel'] = "";
$l['helpPage']['paymentsdate'] = "";

$l['helpPage']['billplanslist'] = "";
$l['helpPage']['billplansnew'] = "";
$l['helpPage']['billplansedit'] = "";
$l['helpPage']['billplansdel'] = "";

$l['helpPage']['billposlist'] = "";
$l['helpPage']['billposnew'] = "";
$l['helpPage']['billposedit'] = "";
$l['helpPage']['billposdel'] = "";

$l['helpPage']['billrateslist'] = "";
$l['helpPage']['billratesnew'] = "";
$l['helpPage']['billratesedit'] = "";
$l['helpPage']['billratesdel'] = "";
$l['helpPage']['billratesdate'] = "";

$l['helpPage']['mngradproxys'] = "";
$l['helpPage']['mngradproxyslist'] = "";
$l['helpPage']['mngradproxysnew'] = "";
$l['helpPage']['mngradproxysedit'] = "";
$l['helpPage']['mngradproxysdel'] = "";

$l['helpPage']['mngradrealms'] = "";
$l['helpPage']['mngradrealmslist'] = "";
$l['helpPage']['mngradrealmsnew'] = "";
$l['helpPage']['mngradrealmsedit'] = "";
$l['helpPage']['mngradrealmsdel'] = "";

$l['helpPage']['mngradattributes'] = "";
$l['helpPage']['mngradattributeslist'] = "";
$l['helpPage']['mngradattributesnew'] = "";
$l['helpPage']['mngradattributesedit'] = "";
$l['helpPage']['mngradattributessearch'] = "";
$l['helpPage']['mngradattributesdel'] = "";
$l['helpPage']['mngradattributesimport'] = "";
$l['helpPage']['mngimportusers'] = "";

$l['helpPage']['msgerrorpermissions'] = "你沒有許可權訪問該頁面。<br/>
請諮詢您的系統管理員。 <br/>";

$l['helpPage']['mngradusergroup'] = "";
$l['helpPage']['mngradusergroupdel'] = "為了從資料庫中刪除使用者條目，您必須提供帳戶的用戶名";
$l['helpPage']['mngradusergroupnew'] = "";
$l['helpPage']['mngradusergrouplist'] = "";
$l['helpPage']['mngradusergrouplistuser'] = "";
$l['helpPage']['mngradusergroupedit'] = "";


$l['helpPage']['mngradprofiles'] = "
<b>Profiles Management</b> - 管理使用者設定檔通過組合一組應答並檢查屬性 <br/>
設定檔可以被認為是組織構成的答覆和檢查組的組成。<br/>
<h200><b>設定檔列表 </b></h200> - List Profiles <br/>
<h200><b>新建設定檔 </b></h200> - Add a Profile <br/>
<h200><b>編輯設定檔 </b></h200> - Edit a Profile <br/>
<h200><b>刪除設定檔 </b></h200> - Delete a Profile <br/>
";
$l['helpPage']['mngradprofilesedit'] = "
<h200><b>編輯個人資料</b></h200> - 編輯個人資料 <br/>
";
$l['helpPage']['mngradprofilesdel'] = "
<h200><b>刪除組配置 </b></h200> - 刪除設定檔資料<br/>
";
$l['helpPage']['mngradprofilesduplicate'] = "
<h200><b>複製檔案 </b></h200> - 複製一個概要檔的屬性設置為一個新建不同的設定檔名稱 <br/>
";
$l['helpPage']['mngradprofileslist'] = "
<h200><b>設定檔列表 </b></h200> - 設定檔列表 <br/>
";
$l['helpPage']['mngradprofilesnew'] = "
<h200><b>新建設定檔</b></h200> - 添加一個設定檔 <br/>
";

$l['helpPage']['mngradgroups'] = "
<b>組織管理</b> - 管理組織回復和組織檢查(radgroupreply/radgroupcheck tables).<br/>
<h200><b>回復/查看列表組 </b></h200> - 回復/查看表組<br/>
<h200><b>搜索組回復/查看 </b></h200> - 搜索一組回復/查看(你可以使用萬用字元) <br/>
<h200><b>新組回復/查看 </b></h200> - 添加一組回復/檢查 <br/>
<h200><b>編輯組回復/查看 </b></h200> - 編輯一組回復/查看地圖<br/>
<h200><b>刪除組回復/查看 </b></h200> - 刪除一個回復/查看地圖 <br/>
";


$l['helpPage']['mngradgroupchecknew'] = "
<h200><b>新組檢查 </b></h200> - 添加一個檢查組 <br/>
";
$l['helpPage']['mngradgroupcheckdel'] = "
<h200><b>刪除組檢查 </b></h200> - 刪除一組檢查 <br/>
";

$l['helpPage']['mngradgroupchecklist'] = "
<h200><b>組織檢查清單 </b></h200> - 組列表檢查 <br/>
";
$l['helpPage']['mngradgroupcheckedit'] = "
<h200><b>編輯組檢查 </b></h200> - 編輯檢查組 <br/>
";
$l['helpPage']['mngradgroupchecksearch'] = "
<h200><b>搜索組檢查 </b></h200> - 搜索一組檢查 <br/>
使用萬用字元，你既可以鍵入 ‘％’ 字元是在熟悉SQL，或者您可以使用更常見‘*’
為方便起見，並daloRADIUS將它翻譯成‘％’
";

$l['helpPage']['mngradgroupreplynew'] = "
<h200><b>新組回復 </b></h200> - 添加一組回答的 <br/>
";
$l['helpPage']['mngradgroupreplydel'] = "
<h200><b>刪除組回復</b></h200> - 刪除一組回答的 <br/>
";
$l['helpPage']['mngradgroupreplylist'] = "
<h200><b>列表組回復</b></h200> - 組回復列表<br/>
";
$l['helpPage']['mngradgroupreplyedit'] = "
<h200><b>編輯組回答 </b></h200> - 編輯回答一組 <br/>
";
$l['helpPage']['mngradgroupreplysearch'] = "
<h200><b>搜索組的回復</b></h200> - 搜索組應答</ 繪圖 <br/>
使用萬用字元，你既可以鍵入 ‘％’ 字元是在熟悉SQL，或者您可以使用更常見‘*’
為方便起見，並daloRADIUS將它翻譯成‘％’
";


$l['helpPage']['mngradippool'] = "
<h200><b>IP位址集區清單</b></h200> - 清單配置IP位址集區及其分配IP地址 <br/>
<h200><b>新建IP位址集區/b></h200> - 添加一個新建IP位址配置IP位址集區 <br/>
<h200><b>編輯IP位址集區</b></h200> - 編輯一個IP位址配置IP位址集區 <br/>
<h200><b>刪除IP位址集區</b></h200> - 刪除一個IP位址從一個配置IP位址集區 <br/>
";
$l['helpPage']['mngradippoollist'] = "<h200><b>IP位址集區清單</b></h200> - 清單配置IP位址集區及其分配IP地址 <br/>";
$l['helpPage']['mngradippoolnew'] = "<h200><b>新建IP位址集區</b></h200> - 添加一個新建IP位址配置IP位址集區 <br/>";
$l['helpPage']['mngradippooledit'] = "<h200><b>編輯IP位址集區</b></h200> - 編輯一個IP位址配置IP位址集區 <br/>";
$l['helpPage']['mngradippooldel'] = "<h200><b>刪除IP位址集區</b></h200> - 刪除一個IP位址從一個配置IP位址集區 <br/>";


$l['helpPage']['mngradnas'] = "";
$l['helpPage']['mngradnasdel'] = "刪除一個nas ip /從資料庫主機條目必須提供的ip /主機帳戶";
$l['helpPage']['mngradnasnew'] = "";
$l['helpPage']['mngradnaslist'] = "";
$l['helpPage']['mngradnasedit'] = "";

$l['helpPage']['mngradhunt'] = "HuntGroup開始工作之前,請閱讀 <a href='http://wiki.freeradius.org/SQL_Huntgroup_HOWTO' target='_blank'>http://wiki.freeradius.org/SQL_Huntgroup_HOWTO</a>.
<br/>
特別是:
...
<i>找到你的radiusd.conf或網站功能/ defaut設定檔中的授權部分和編輯它。在預處理模組後，授權部分的頂部插入這些行：</i>
<br/>
<pre>
update request {
    Huntgroup-Name := \"%{sql:select groupname from radhuntgroup where nasipaddress=\\\"%{NAS-IP-Address}\\\"}\"
}
</pre>
<i> 這是使用IP位址作為回報huntgroup名字中的一個重要radhuntgroup表中執行查找。然後添加一個屬性/值對該請求的屬性名稱是huntgroup的名字和它的值就是從SQL查詢返回的。如果查詢沒有發現任何值是空字串。 </i>";


$l['helpPage']['mngradhuntdel'] = "從資料庫中刪除組條目必須提供的ip /主機和埠id";
$l['helpPage']['mngradhuntnew'] = "";
$l['helpPage']['mngradhuntlist'] = "";
$l['helpPage']['mngradhuntedit'] = "";

$l['helpPage']['mnghsdel'] = "從資料庫中刪除一個熱點必須提供熱點的名稱<br/>";
$l['helpPage']['mnghsedit'] = "您可以編輯以下細節熱點<br/>";
$l['helpPage']['mnghsnew'] = "您可以填寫以下細節的新熱點除了資料庫";
$l['helpPage']['mnghslist'] = "資料庫中的所有熱點的清單。您可以使用快速連結來編輯或刪除資料庫中的一個熱點。";

$l['helpPage']['configdb'] = "
<b>資料庫設置</b> - 配置資料庫引擎，連接設置，表名，如果
預設沒有被使用，並在資料庫中的口令加密類型.<br/>
<h200><b>全域設置</b></h200> - 資料庫存儲引擎<br/>
<h200><b>表設置</b></h200> - 如果不使用預設FreeRADIUS模式你可以改變名字
表的名稱<br/>
<h200><b>高級設置</b></h200> - 你想在資料庫中存儲使用者的密碼不在是
純文字,而是讓它以某種方式你可以選擇一個MD5或加密<br/>
";
$l['helpPage']['configlang'] = "
<h200><b>語言設置</b></h200> - 配置介面語言<br/>
";
$l['helpPage']['configuser'] = "
<h200><b>使用者設置</b></h200> - 配置使用者管理行為。<br/>
";
$l['helpPage']['configmail'] = "
<h200><b>使用者設置</b></h200> - 配置郵件設置。<br/>
";
$l['helpPage']['configlogging'] = "
<h200><b>日誌設置</b></h200> - 配置日誌規則和設施 <br/>
請確保您指定的檔案名寫許可權的網路服務器<br/>";
$l['helpPage']['configinterface'] = "
<h200><b>介面設置</b></h200> - 配置介面佈局設置和behvaiour <br/>
";
$l['helpPage']['configmain'] = "
<b>全域設置</b><br/>
<h200><b>資料庫設置</b></h200> - 配置資料庫引擎，連接設置，表名，如果
預設沒有被使用，並在資料庫中的口令加密的類型。<br/>
<h200><b>語言設置</b></h200> - 配置介面語言。<br/>
<h200><b>語言設置</b></h200> - 配置日誌記錄的規則和設施 <br/>
<h200><b>介面設置</b></h200> - 配置介面佈局設置和behvaiour <br/>

<b>子類配置</b>
<h200><b>維護</b></h200> - 維護選項用於測試使用者連接或終止會話 <br/>
<h200><b>設備/b></h200> - 設備配置存取控制清單(ACL) <br/>
";
$l['helpPage']['configbusiness'] = "
<b>業務資訊</b><br/>
<h200><b>業務聯繫</b></h200> - 設置業務連絡人資訊(所有者、標題、位址、電話等)<br/>
";
$l['helpPage']['configbusinessinfo'] = "";
$l['helpPage']['configmaint'] = "
<b>維護</b><br/>
<h200><b>測試用戶連接</b></h200> - 發送一個訪問請求的RADIUS伺服器檢查用戶憑證是有效的<br/>
<h200><b>斷開連接的用戶</b></h200> - 發出一個POD（包斷開連接）或CoA（改變許可權）的資料包NAS伺服器
要斷開用戶並在一個特定的NAS終止他/她會話。<br/>
";
$l['helpPage']['configmainttestuser'] = "
<h200><b>測試用戶連接</b></h200> - RADIUS伺服器的訪問請求發送給檢查用戶憑證是否有效。<br/>
ddaloradius使用RADIUS用戶端二進位實用程式來執行測試並返回命令結果完成後。 <br/>
daloRADIUS計數的RADIUS用戶端的二進位檔案在\$ PATH環境變數可用，如果不是，請
更正庫/exten-maint-radclient.php 文件<br/><br/>

請注意，它可能需要一段時間的測試完成（幾秒[ 10-20秒左右]）由於故障和
radclient將重發的數據包。

在“高級”選項卡可以調整測試選項：<br/>
超時等待超時秒後重試（可能是一個浮點數）<br/>
如果超時重試，重試發送該資料包的重試的次數。<br/>
計數發送每個資料包的數倍<br/>
從並行檔請求發送的資料包數<br/>
";
$l['helpPage']['configmaintdisconnectuser'] = "
<h200><b>斷開用戶</b></h200> - 發出一個POD（包斷開連接）或CoA（改變許可權）的資料包NAS伺服器
要斷開用戶並在一個特定的NAS終止他/她會話。<br/>
終止用戶會話，要求在NAS支持POD或AOC包類型，請諮詢您的NAS設備或
文檔這一點。此外，它需要知道在NAS埠POD或AOC資料包，而較新建NAS的使用埠3799
而其他的被配置成接收在埠1700的資料包。

ddaloradius使用RADIUS用戶端二進位實用程式來執行測試並返回命令結果完成後。 <br/>
daloRADIUS計數的RADIUS用戶端的二進位檔案在\$ PATH環境變數可用，如果不是，請
更正庫/exten-maint-radclient.php 文件<br/><br/

請注意，它可能需要一段時間的測試完成（幾秒[ 10-20秒左右]）由於故障和
radclient將重發的數據包。

在“高級”選項卡可以調整測試選項：<br/>
超時等待超時秒後重試（可能是一個浮點數）<br/>
如果超時重試，重試發送該資料包的重試的次數。<br/>
計數發送每個資料包的數倍<br/>
從並行檔請求發送的資料包數<br/>


";
$l['helpPage']['configoperatorsdel'] = "從資料庫中刪除的操作員必須提供用戶名。";
$l['helpPage']['configoperatorsedit'] = "下面編輯設備使用者詳細資訊";
$l['helpPage']['configoperatorsnew'] = "你可以填寫下面的一個新建設備的使用者除了資料庫的詳細資訊";
$l['helpPage']['configoperatorslist'] = "顯示所有設備的資料庫";
$l['helpPage']['configoperators'] = "設備的配置";
$l['helpPage']['configbackup'] = "執行備份";
$l['helpPage']['configbackupcreatebackups'] = "創建備份";
$l['helpPage']['configbackupmanagebackups'] = "管理備份";


$l['helpPage']['graphmain'] = "
<b>圖表</b><br/>
<h200><b>總體登錄/點擊</b></h200> - 繪製的每一段時間內的特定使用者的使用情況圖表。
所有登錄 （或 '點擊' 到 NAS） 是通過圖形方式顯示以及表格清單。<br/>
<h200><b>總下載統計</b></h200> - 繪製的每一段時間內的特定使用者的使用情況圖表
由用戶端下載的資料量是正在被計算的值。該圖伴隨下載量即時顯示<br/>
<h200><b>總體上傳統計</b></h200> - 繪製的每一段時間內的特定使用者的使用情況圖表。
由用戶端上傳的資料量是正在被計算的值。該圖伴隨上傳量即時顯示<br/>
<br/>
<h200><b>所有時間登錄/點擊</b></h200> - 繪出登錄到伺服器上的給定時間週期的圖形圖表。<br/>
<h200><b>所有流量對比</b></h200> - 繪製圖表的下載和上傳 statisticse.</br>
<h200><b>登錄用戶</b></h200> - 繪製指定期間中的登錄的使用者的圖表
按天、 月、 年僅按月份和年份圖每小時圖或篩選器篩選 （選擇 \"---\"天） 圖的最小和最大登錄的用戶在所選的一個月.
";
$l['helpPage']['graphsalltimelogins'] = "登錄到伺服器的歷史統計資料基於分佈在一段時間內";
$l['helpPage']['graphsalltimetrafficcompare'] = "通過伺服器基於分佈在一段時間內流量資料統計。";
$l['helpPage']['graphsloggedusers'] = "繪製已登錄的總的圖表";
$l['helpPage']['graphsoveralldownload'] = "繪製圖表伺服器的已下載位元組數";
$l['helpPage']['graphsoverallupload'] = "繪製圖表的上傳到伺服器的位元組";
$l['helpPage']['graphsoveralllogins'] = "繪製圖表對伺服器的登錄嘗試";



$l['helpPage']['rephistory'] = "顯示所有活動執行管理專案和提供資訊<br/>
創建日期,創建和更新日期和更新歷史領域";
$l['helpPage']['replastconnect'] = "顯示所有RADIUS伺服器的登錄嘗試,成功和失敗的登錄";
$l['helpPage']['replogsboot'] = "監控作業系統開機記錄——相當於運行dmesg命令。";
$l['helpPage']['replogsdaloradius'] = "監控daloRADIUS的日誌檔";
$l['helpPage']['replogsradius'] = "監控FreeRADIUS的日誌檔。";
$l['helpPage']['replogssystem'] = "監控作業系統日誌檔。";
$l['helpPage']['rephb'] = "";
$l['helpPage']['rephbdashboard'] = "";
$l['helpPage']['repbatch'] = "";
$l['helpPage']['repbatchlist'] = "";
$l['helpPage']['mngbatchlist'] = "";
$l['helpPage']['mngbatchdel'] = "";
$l['helpPage']['repbatchdetails'] = "提供了一個活躍使用者的這批實例的清單";
$l['helpPage']['replogs'] = "
<b>Logs</b><br/>
<h200><b>daloRADIUS日誌</b></h200> - 監控daloRADIUS的日誌檔。<br/>
<h200><b>RADIUS日誌</b></h200> - 監控FreeRADIUS的日誌檔,在 /var/log/freeradius/radius.log 或 /usr/local/var/log/radius/radius.log.
日誌檔可能在其他可能的地方,如果是這樣的話請相應地調整配置.<br/>
<h200><b>系統日誌</b></h200> - 監控作業系統日誌檔,在 /var/log/syslog or /var/log/消息在大多數平臺上。
日誌檔可能在其他可能的地方,如果是這樣的話請相應地調整配置。<br/>
<h200><b>Boot Log</b></h200> - 監控作業系統開機記錄——相當於運行dmesg命令。
";
$l['helpPage']['repmain'] = "
<b>普通的報告</b><br/>
<h200><b>線上用戶</b></h200> - 提供了一個清單的所有使用者 
發現線上通過會計表在資料庫中。為用戶正在執行的檢查
沒有結束時間(AcctStopTime)。重要的是要注意,這些用戶也會過期的會話
這當NASs由於某種原因未能發送accounting-stop包。.<br/>
<h200><b>Last Connection Attempts</b></h200> - 提供所有Access-Accept的清單和Access-Reject(接受和失敗)登錄為用戶。 <br/>
這些從資料庫的postauth表需要定義FreeRADIUS設定檔的實際記錄這些.<br/>
<h200><b>用戶使用詳情</b></h200> - 提供了一個清單的前N使用者頻寬消耗和會話時間使用br/><br/>
<b>Sub-範疇的報告</b><br/>
<h200><b>Logs</b></h200> - 提供daloRADIUS日誌檔、FreeRADIUSs日誌檔案系統的日誌檔和開機記錄檔<br/>
<h200><b>Status</b></h200> - 提供伺服器狀態資訊和RADIUS元件狀態";
$l['helpPage']['repstatradius'] = "提供關於伺服器本身的一般資訊:CPU使用率,流程,正常執行時間、記憶體使用情況,等等";
$l['helpPage']['repstatserver'] = "提供關於FreeRADIUS守護進程的一般資訊和MySQL資料庫伺服器";
$l['helpPage']['repstatus'] = "<b>狀態</b><br/>
<h200><b>伺服器狀態</b></h200> - 提供關於伺服器本身的一般資訊:CPU使用率,流程,正常執行時間、記憶體使用情況,等等。<br/>
<h200><b>RADIUS 狀態</b></h200> - 提供關於FreeRADIUS守護進程的一般資訊和MySQL資料庫伺服器";
$l['helpPage']['reptopusers'] = "下面顯示記錄為高級使用者,那些獲得了最高消費的會話
時間和頻寬使用情況。清單的使用者類別: ";
$l['helpPage']['repusername'] = "記錄發現的使用者:";
$l['helpPage']['reponline'] = "
下表顯示了當前連接使用者
系統。非常有可能,有陳舊的連接,
這意味著用戶掉線但NAS沒有發送或不是
能夠發送停止會計包RADIUS伺服器。";

$l['helpPage']['mnglistall'] = "清單中的使用者資料庫";
$l['helpPage']['mngsearch'] = "搜索用戶： ";
$l['helpPage']['mngnew'] = "您可以填寫以下資訊新使用者除了資料庫<br/>";
$l['helpPage']['mngedit'] = "編輯下面的使用者詳細資訊<br/>";
$l['helpPage']['mngdel'] = "為了從資料庫中刪除使用者條目，你必須提供帳戶的用戶名<br/>";
$l['helpPage']['mngbatch'] = "您可以填寫以下資訊新使用者除了資料庫。<br/>
請注意，這些設置將適用於所有你所創建的用戶。<br/>";
$l['helpPage']['mngnewquick'] = "下面的用戶/卡是預付費類型。<br/>
在時間信用證規定的時間內將被用作 Session-Timeout（會話超時） 和 Max-All-Session（最大-所有-會話） RADIUS屬性";

// 帳單部分
$l['helpPage']['acctactive'] = "
	規定，將被證明是用於跟蹤活動或過期的資料庫中的使用者有用的資訊
其中有一個到期屬性或馬克斯 - 所有會話屬性的使用者而言。
<br/>
";
$l['helpPage']['acctall'] = "
	為資料庫中的所有會話的完整的會計資訊。
<br/>
";
$l['helpPage']['acctdate'] = "
	為給定的2日期為特定用戶之間的所有會話完整的會計資訊。
<br/>
";
$l['helpPage']['acctipaddress'] = "
	為起源與特定IP位址的所有會話的完整的會計資訊。
<br/>
";

$l['helpPage']['acctplans'] = "";
$l['helpPage']['acctmain'] = "
<b>General Accounting</b><br/>
<h200><b>User Accounting</b></h200> - 
	為資料庫中的一個特定使用者的所有會話的完整的會計資訊。
<br/>
<h200><b>IP Accounting</b></h200> - 
	為起源與特定IP位址的所有會話的完整的會計資訊。
<br/>
<h200><b>NAS Accounting</b></h200> - 
	為所有的特定NAS的IP位址已辦理了全面的會話計費資訊。
<br/>
<h200><b>Date Accounting</b></h200> - 
	Provides對於給定的2日期為特定用戶之間的所有會話完整的會計資訊。
<br/>
<h200><b>All Accounting Records</b></h200> - 
	為資料庫中的所有會話的完整的會計資訊。
<br/>
<h200><b>Active Records Accounting</b></h200> - 
	規定，將被證明是用於跟蹤活動或過期的資料庫中的使用者有用的資訊
其中有一個到期屬性或 Max-All-Session（最大-所有-會話）屬性的使用者而言。
<br/>

<br/>
<b>Sub-Category Accounting</b><br/>
<h200><b>Custom</b></h200> - 
	提供了最靈活的自訂查詢到資料庫上運行。
<br/>
<h200><b>Hotspots</b></h200> - 
	提供不同的管理熱點資訊、比較,和其他有用的資訊。
<br/>
";
$l['helpPage']['acctnasipaddress'] = "
	提供完整的會計資訊的所有會話的具體處理NAS IP位址。
<br/>
";
$l['helpPage']['acctusername'] = "
	提供完整的會計資訊對特定使用者的資料庫中的所有會話。
<br/>
";
// accounting hotspot section
$l['helpPage']['accthotspotaccounting'] = "
	提供完整的會計資訊的所有會話起源於這個特定的熱點。
這個清單是計算清單只有那些與CalledStationId radacct表中的記錄
欄位匹配一個熱點中的熱點的MAC位址條目的管理資料庫。
<br/>
";
$l['helpPage']['accthotspotcompare'] = "
	提供了基本的會計資訊比較資料庫中找到的所有活躍的熱點。
       會計提供的資訊:< br / > < br / >
    熱點名稱——熱點的名稱< br / >
    獨特的用戶-用戶已登陸,只有通過這個熱點< br / >
    總點擊——總登錄,進行從這個熱點(獨特的和非獨特的)< br / >
    平均時間——平均時間用戶花在這個熱點< br / >
    總時間——所有用戶的accumolated花時間在這個熱點<br/>

<br/>
	提供了一個圖塊不同的比較了< br / >
    圖:< br / > < br / >
    每個熱點分佈的獨特用戶< br / >
    分配每個熱點的點擊< br / >
    每個熱點分佈的時間使用 <br/>
<br/>
";
$l['helpPage']['accthotspot'] = "
<h200><b>Hotspot Accounting</b></h200> - 
	提供完整的會計資訊的所有會話起源於這個特定的熱點。
<br/>
<h200><b>Hotspot Comparison</b></h200> - 
	提供了基本的會計資訊比較資料庫中找到的所有活躍的熱點。
提供了一個圖塊不同的比較。
<br/>
";
// 會計自訂查詢部分
$l['helpPage']['acctcustom'] = "
<h200><b>Custom</b></h200> - 
	提供最靈活的自訂查詢資料庫上運行。< br / >
你可以調整查詢的max通過修改設置在左側欄。< br / >
<br/>
	<b> 日期< / b > -設置開始和結束日期.
<br/>
	<b> < / b >——設置資料庫中的欄位(像一個鍵)你想匹配,選擇如果值
比賽應該等於(=)或它包含你搜索的一部分價值(如一個規則運算式)。如果你
選擇使用包含操作符你不應該添加任何常見的萬用字元“*”而是
您輸入的值將自動搜索這種形式:* *價值(或mysql風格:%值%)。
<br/>
	< b > < / b >查詢會計領域,你可以選擇你想要的欄位出現在結果中
列表。
< br / >
< b > < / b >訂單——選擇你想訂場的結果和它的類型(提升
或降冪)
< br / >
";
$l['helpPage']['acctcustomquery'] = "";
$l['helpPage']['acctmaintenance'] = "
<h200><b>清理過期會話</b></h200> - 
	‘過期會話’可能經常存在因為會影響NAS無法提供計費停止紀錄<<br/>
	如不不清理長時間的過期用戶會話，會導致假的使用者登錄記錄的存在
	記錄 (false positive).
<br/>
<h200><b>刪除會計記錄</b></h200> - 
	刪除資料庫中的會計記錄。要執行該操作，或者要允許其他用戶。
	除了管理員訪問這個頁面。
<br/>
";
$l['helpPage']['acctmaintenancecleanup'] = "";
$l['helpPage']['acctmaintenancedelete'] = "";



$l['helpPage']['giseditmap'] = "
	編輯地圖模式，在這種模式下你可以簡單地通過點擊添加或刪除熱點
在地圖上的位置或通過點擊一個熱點（分別）<br/><br/>
	<b> 添加熱點 </b> - 只需點擊一個清晰的地圖上的位置,你將提供
熱點的名稱和它的MAC位址。這些關鍵細節後用於識別這個熱點
在會計表中。務必提供正確的MAC地址！
<br/><br/>
	<b> 刪除熱點 </b> - 只需點擊一個熱點的圖示，你確定它刪除從
資料庫。
<br/>
";
$l['helpPage']['gisviewmap'] = "
查看地圖模式-在此模式下你可以流覽他們的熱點進行佈局
在利用GoogleMaps服務提供的地圖圖示。<br/><br/>

	<b> 點擊一個熱點 </b> -將提供您更深入的細節上的熱點。
	如聯繫資訊的熱點，統計資訊。
<br/>
";
$l['helpPage']['gismain'] = "
<b> 一般資訊 </b>
GIS熱點位置的提供了視覺化世界各地的地圖使用Google Maps API。< br / >
在管理頁面你可以向資料庫添加新建熱點條目,那裡也是一個欄位
稱為地理位置,這是Google Maps API使用以有定位的準確數值
位置在地圖上的熱點。<br/><br/>

<h200><b>2 提供的操作模式:</b></h200> 
一個是<b>查看地圖</b>模式使“飆網”通過世界地圖
查看當前位置的熱點在資料庫和另一個<b>編輯地圖</b> -該模式
一個可以使用以創建熱點的直觀簡單的左點擊地圖或刪除
現有的熱點條目，左鍵按一下現有熱點的旗幟。.<br/><br/>

另一個重要的問題是,網路上的每台電腦需要一個獨特的註冊碼,你
從Google Maps API頁面可以獲得通過提供完整的web託管目錄的位址嗎
daloRADIUS伺服器上的應用程式。一旦你從穀歌獲得代碼,只需粘貼的
註冊框,然後按一下“註冊碼”按鈕來寫它。
然後你可以使用穀歌地圖服務。 <br/><br/>";

/* ********************************************************************************** */



$l['messages']['noCheckAttributesForUser'] = "這個用戶沒有檢查相關聯的屬性";
$l['messages']['noReplyAttributesForUser'] = "這個用戶沒有回復相關聯的屬性";

$l['messages']['noCheckAttributesForGroup'] = "這個組沒有檢查相關聯的屬性";
$l['messages']['noReplyAttributesForGroup'] = "這個組沒有回復相關聯的屬性";

$l['messages']['nogroupdefinedforuser'] = "這個用戶沒有相關聯的組";
$l['messages']['wouldyouliketocreategroup'] = "你想創建一個？";


$l['messages']['missingratetype'] = "錯誤：缺失價格類型";
$l['messages']['missingtype'] = "錯誤：丟失類型";
$l['messages']['missingcardbank'] = "錯誤：丟失銀行卡";
$l['messages']['missingrate'] = "錯誤：丟失價格";
$l['messages']['success'] = "成功";
$l['messages']['gisedit1'] = "歡迎,你目前在編輯模式";
$l['messages']['gisedit2'] = "從地圖和資料庫刪除當前標記?";
$l['messages']['gisedit3'] = "請輸入熱點的名稱";
$l['messages']['gisedit4'] = "添加當前標記到資料庫嗎?";
$l['messages']['gisedit5'] = "請輸入熱點的名稱";
$l['messages']['gisedit6'] = "請輸入MAC熱點的位址";

$l['messages']['gismain1'] = "成功更新穀歌地圖API註冊碼";
$l['messages']['gismain2'] = "錯誤:無法打開文件寫入";
$l['messages']['gismain3'] = "檢查檔的許可權。這個檔應該是網路服務器的使用者/組可寫的。";
$l['messages']['gisviewwelcome'] = "歡迎來到Enginx視覺地圖";

$l['messages']['loginerror'] = "<br/><br/>下麵之一：<br/>
1. 錯誤的用戶名/密碼<br/>
2. 管理員已經登錄的（只允許一個實例）<br/>
3. 似乎有不止一個的管理員的使用者在資料庫中<br/>
";

$l['buttons']['savesettings'] = "保存設置";
$l['buttons']['apply'] = "應用";

$l['menu']['Home'] = "<em>主</em>頁</a>";
$l['menu']['Managment'] = "<em>管</em>理</a>";
$l['menu']['Reports'] = "<em>報</em>告</a>";
$l['menu']['Accounting'] = "<em>賬</em>單</a>";
$l['menu']['Billing'] = "<em>記</em>賬</a>";
$l['menu']['Gis'] = "<em>G</em>IS</a>";
$l['menu']['Graphs'] = "<em>圖</em>表</a>";
$l['menu']['Config'] = "<em>配</em>置</a>";
$l['menu']['Help'] = "<em>幫</em>助</a>";


?>

