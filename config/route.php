<?php
use core\Route;
// 首页
Route::get('/','IndexController@index');
// 归档
Route::get('archives','ArchivesController@index');
Route::get('page/(:num)','IndexController@index');
Route::get('tags','TagsController@index');
Route::get('tags/(:any)','TagsController@index');
Route::get('search/(:any)','IndexController@search');
Route::get('cat','CatController@index');
Route::get('cat/(:num)','CatController@item');
// 标签
Route::get('article','ArticleController@index');
// 文章
Route::get('article','ArticleController@index');
Route::get('article/(:num)','IndexController@article');
// 读书
Route::get('book','BookController@index');
Route::get('book/(:num)','BookController@show');
// 照片
Route::get('photo','PhotoController@index');
Route::get('photo/cat/(:num)','PhotoController@index');
Route::get('photo/(:num)','PhotoController@show');

// 日记
Route::get('diary','DiaryController@index');
// 运动
Route::get('motion','MotionController@index');
Route::get('motion/(:num)','MotionController@index');
// 笔记
Route::get('notebook','NotebookController@index');
Route::get('notebook/(:num)','NotebookController@cover');
Route::get('notebook/detail/(:num)','NotebookController@detail');
// 项目
Route::get('item','ItemController@index');
Route::get('item/(:num)','ItemController@show');
// 关于我
Route::get('about','AboutController@index');
// 时间事件日志
Route::get('ett','EventTaskTimeController@index');
Route::get('ett/add','EventTaskTimeController@add');
Route::get('ett/(:num)','EventTaskTimeController@index');
Route::post('ett/add','EventTaskTimeController@save');

// OKR
Route::get('okr','OkrController@index');
Route::get('okr/(:num)','OkrController@index');
// 后台
Route::get('login','Admin.LoginController@index');
Route::post('login','Admin.LoginController@login');

// 控制面板
Route::get('admin/year/(:any)','Admin.IndexController@index');
Route::post('admin/habits/add','Admin.IndexController@add');
Route::get('admin/habits/(:num)/del','Admin.IndexController@del');
Route::get('admin/habits/(:num)/complete','Admin.IndexController@complete');
Route::post('admin/habits/annal','Admin.IndexController@annal');
Route::post('admin/goodhabits/add','Admin.IndexController@addGoodHabits');
Route::get('admin/goodhabits/(:num)/del','Admin.IndexController@delGoodHabits');
Route::post('admin/goodhabits/edit','Admin.IndexController@editGoodHabits');

Route::get('admin','Admin.IndexController@index');
Route::get('admin/profile','Admin.ProfileController@index');
Route::post('admin/profile','Admin.ProfileController@update');
Route::get('admin/settings','Admin.SettingsController@index');
Route::post('admin/settings','Admin.SettingsController@update');
Route::get('admin/logout','Admin.IndexController@logout');

// 任务管理
Route::get('admin/task','Admin.TaskController@index');
Route::post('admin/task/add','Admin.TaskController@add');

Route::get('admin/task/task/edit/(:num)','Admin.TaskController@TodayTaskEdit');


Route::get('admin/task/task/(:all)','Admin.TaskController@TodayTask');
Route::get('admin/task/(:num)/cancel','Admin.TaskController@TodayTaskCancel');
Route::get('admin/task/(:num)/del','Admin.TaskController@TodayTaskDel');
Route::post('admin/task/(:num)/update','Admin.TaskController@TodayTaskUpdate');
Route::get('admin/task/complete/(:num)','Admin.TaskController@TodayComplete');
Route::get('admin/task/timeline','Admin.TaskController@TodayTimeline');
Route::post('admin/task/project/add','Admin.TaskController@projectAdd');
Route::get('admin/task/project','Admin.TaskController@getProjectTask');
Route::get('admin/task/(:num)','Admin.TaskController@ProjectTaskItem');

// 任务

Route::post('admin/task/(:num)/save','Admin.TaskController@save');
Route::get('admin/task/(:num)/edit','Admin.TaskController@edit');
Route::post('admin/task/(:num)/update','Admin.TaskController@update');
Route::get('admin/task/(:num)/del','Admin.TaskController@del');

// 计划
Route::post('admin/okr/add','Admin.OkrController@add');
Route::get('admin/okr/(:num)/edit','Admin.OkrController@edit');
Route::get('admin/okr/(:num)','Admin.OkrController@getOkr');
Route::get('admin/okr/(:num)/del','Admin.OkrController@del');
Route::get('admin/okr/task','Admin.OkrController@index');
Route::get('admin/okr/task/(:num)','Admin.OkrController@index');
Route::post('admin/okr/(:num)/update','Admin.OkrController@update');
Route::get('admin/okr/(:num)/cancel','Admin.OkrController@cancel');
Route::post('admin/okr/item/post','Admin.OkrItemController@post');
Route::get('/admin/okr/item/list/(:num)','Admin.OkrItemController@getOkrItemList');
Route::get('admin/okr/item/(:num)/del','Admin.OkrItemController@del');
Route::get('admin/okr/item/(:num)/cancel','Admin.OkrItemController@cancel');
Route::post('admin/okr/item/complete','Admin.OkrItemController@complete');



// 项目任务
Route::get('admin/projectTask/(:num)/edit','Admin.TaskController@addProjectTask');
Route::get('admin/projectTask/(:num)/del','Admin.TaskController@delProjectTask');
Route::post('admin/projectTask/(:num)/update','Admin.TaskController@updateProjectTask');
// 项目任务子任务

Route::get('admin/project/item/(:num)','Admin.ProjectTaskController@index');
Route::post('admin/project/item/add','Admin.ProjectTaskController@add');
Route::get('admin/project/task/(:num)/edit','Admin.ProjectTaskController@edit');
Route::post('admin/project/item/(:num)/update','Admin.ProjectTaskController@update');
Route::get('admin/project/item/(:num)/del','Admin.ProjectTaskController@del');
Route::get('/admin/project/item/(:num)/(:num)/complete','Admin.ProjectTaskController@complete');
Route::get('/admin/project/item/(:num)/(:num)/cancel','Admin.ProjectTaskController@cancel');

// 项目备注
Route::post('/admin/project/replay','Admin.ProjectTaskReplayController@add');
Route::get('admin/project/item/replay/(:num)/(:num)','Admin.ProjectTaskReplayController@index');
Route::get('admin/project/item/replay/(:num)','Admin.ProjectTaskReplayController@index');
Route::get('/admin/project/replay/(:num)/del','Admin.ProjectTaskReplayController@del');

// 文章管理
Route::get('admin/article','Admin.ArticleController@index');
Route::get('admin/article/add','Admin.ArticleController@add');
Route::post('admin/article/save','Admin.ArticleController@save');
Route::get('admin/article/(:num)/edit','Admin.ArticleController@edit');
Route::post('admin/article/(:num)/update','Admin.ArticleController@update');
Route::get('admin/article/(:num)/del','Admin.ArticleController@del');
Route::get('admin/article/(:num)/status','Admin.ArticleController@status');

// 笔记本
Route::get('admin/notebook','Admin.NotebookController@index');
Route::post('admin/notebook/add','Admin.NotebookController@add');
Route::post('admin/notebook/(:num)/save','Admin.NotebookController@save');
Route::get('admin/notebook/(:num)/edit','Admin.NotebookController@cover');
Route::post('admin/notebook/(:num)/edit','Admin.NotebookController@UpdateCover');
Route::get('admin/notebook/(:num)/del','Admin.NotebookController@bookDel');
Route::post('admin/notebook/(:num)/edit/create','Admin.NotebookController@createNotes');
Route::get('admin/notebook/(:num)/edit/(:num)','Admin.NotebookController@edit');
Route::post('admin/notebook/(:num)/edit/(:num)','Admin.NotebookController@update');
Route::post('admin/notebook/(:num)/edit/(:num)/update','Admin.NotebookController@noteUpdate');
Route::get('admin/notebook/(:num)/edit/(:num)/del','Admin.NotebookController@noteDel');

// 日记本
Route::get('admin/diary','Admin.DiaryController@index');
Route::get('admin/diary/add','Admin.DiaryController@add');
Route::post('admin/diary/save','Admin.DiaryController@save');
Route::get('admin/diary/list','Admin.DiaryController@getDiaryList');
Route::get('admin/diary/(:num)/edit','Admin.DiaryController@edit');
Route::get('admin/diary/(:num)/update','Admin.DiaryController@update');
Route::get('admin/diary/(:num)/del','Admin.DiaryController@del');

// 项目管理
Route::get('admin/item','Admin.ItemController@index');
Route::get('admin/item/add','Admin.ItemController@add');
Route::post('admin/item/(:num)/save','Admin.ItemController@save');
Route::get('admin/item/(:num)/edit','Admin.ItemController@edit');
Route::post('admin/item/(:num)/update','Admin.ItemController@update');
Route::get('admin/item/(:num)/del','Admin.ItemController@del');

// 留言管理
Route::get('admin/comment','Admin.MotionController@index');
Route::get('admin/comment/add','Admin.MotionController@add');
Route::post('admin/comment/(:num)/save','Admin.MotionController@save');
Route::get('admin/comment/(:num)/edit','Admin.MotionController@edit');
Route::post('admin/comment/(:num)/update','Admin.MotionController@update');
Route::get('admin/comment/(:num)/del','Admin.MotionController@del');

// 到深圳的日子里
Route::get('admin/shenzhen','Admin.ShenzhenController@index');
Route::post('admin/shenzhen/save','Admin.ShenzhenController@save');

// 日程表
Route::get('admin/schedule','Admin.ScheduleController@index');
Route::post('admin/schedule/getTask','Admin.ScheduleController@getTask');

Route::post('admin/schedule/add','Admin.ScheduleController@add');
Route::post('admin/schedule/edit','Admin.ScheduleController@edit');
Route::post('admin/schedule/del','Admin.ScheduleController@del');

Route::post('admin/schedule/cat/get','Admin.ScheduleController@getCat');
Route::post('admin/schedule/cat/add','Admin.ScheduleController@addCat');
Route::post('admin/schedule/cat/del','Admin.ScheduleController@delCat');

// 摄影
Route::get('admin/photo','Admin.PhotoController@index');
Route::get('admin/photo/add','Admin.PhotoController@add');
Route::post('admin/photo/save','Admin.PhotoController@save');
Route::get('admin/photo/(:num)/edit','Admin.PhotoController@edit');
Route::post('admin/photo/(:num)/update','Admin.PhotoController@update');
Route::get('admin/photo/(:num)/status','Admin.PhotoController@status');
Route::get('admin/photo/(:num)/del','Admin.PhotoController@del');
Route::post('admin/photo/(:num)/save','Admin.PhotoController@photo_item_add');
Route::get('/admin/photo/item/(:num)/del','Admin.PhotoController@photo_item_del');


Route::dispatch();