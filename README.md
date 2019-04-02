#项目介绍

###开发环境
- 操作系统 MAC OS
- 开发平台 Docker
- web服务器 Nginx
- 数据库 MySQL 5.7 PXC集群
- 编程语言 PHP 7.1 php-fpm
- 缓存 Redis
- 版本管理 Git

--
###服务端代码
- 框架 Laravel 5.7
- 使用到的特性 

> 表格下面有代码示例，摘自于项目源码。

| 特性| 描述 | 应用场景 |
| :-: | :-: | :-: |
| Notification | 消息通知 | 被其他人关注时消息通知 |
| Policy | 用户授权 | 只有自己才能编辑自己发布的问题 |
| Request  | 自定义验证 | 发布问题时验证字段的规则以及返回的错误提示 |
| Collection | 集合 | 对数据进行处理 |
| Reids | 缓存 | 对热数据进行缓存 |
| Eloquent ORM | 关系模型 |  |
| - | API 资源：Resource | 对API返回数据进行处理 |
| - | 模型关联/多态关联 | 代替部分Join功能 |
| - | 修改器/访问器 | 插入/查看数据时，对数据进行预处理 |
| Passport | 用户认证 | 实现OAuth2.0 对API接口的访问进行认证 |
| 实时Facades | 实时Facades | laravel会通过容器解析这个类 |


   - 消息通知：Notification 
   
   ```
   //发送私信后 给用户一个提示
   class SendMessageNotification extends Notification
    {
        ...
    
        /**
         * Get the notification's delivery channels.
         *
         * @param  mixed $notifiable
         * @return array
         */
        public function via($notifiable)
        {
            return ['database'];
        }
    
        public function toDatabase($notifiable)
        {
            return [
                'name' => apiUser()->name ?? user()->name,
                'data' => $this->body
            ];
        }
    
        ...
    }
   ```
   
   ```
   class MessageRepository
    {
        ...
            
        /**
         * @param $params
         * @return mixed
         */
        public function sendMessage($params)
        {
            $user   = User::find($params['to_user_id']);
            $result = $this->message->create([
                'to_user_id'   => $params['to_user_id'],
                'from_user_id' => apiUserId() ?? userId(),
                'body'         => $params['body'],
                'dialog_id'    => $this->buildDialogId($params['to_user_id']),
            ]);
            /**
             *———————————————————
             * 在这里调用消息通知  |
             *———————————————————
             */
            $user->notify(new SendMessageNotification($params['body']));
    
            return $result;
    
        }
    
        ...
        
    }
   ```
   
   - 用户授权：Policy

   ```
   class QuestionPolicy
    {
        ...
        
        /**
         * Determine whether the user can update the question.
         *
         * @param  \App\User $user
         * @param  \App\Question $question
         * @return mixed
         */
        public function update(User $user, Question $question)
        {
            if ( $user->id != $question->user_id ) {
                flash('您只能编辑自己发布的问题','danger');
                return false;
            }else{
                return true;
            }
    
        }
    
        ...
        
    }
   ```
   
   ```
    class QuestionsController extends Controller
    {
        ...
    
        /**
         * Show the form for editing the specified resource.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {
            $question = $this->question_repository->byIdWithTopic($id);
            /**
             *—————————————————————————
             * 通过can方法判断是否有权限  |
             *—————————————————————————
             */
            if ( Auth::user()->can('update', $question) ) {
                return view('questions.edit', compact('question'));
            }
    
            return back();
        }
    
        ...
        
    }

   ```
   
   - 自定义验证：Request

   ```
    
    namespace App\Http\Requests;
    
    use Illuminate\Foundation\Http\FormRequest;
    
    class QuestionRequest extends FormRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize()
        {
            return true;
        }
    
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules()
        {
            return [
                'title'=>'required|min:6|max:50',
                'body'=>'required'
            ];
        }
    }

   ```
   
   ```
    class QuestionsController extends Controller
    {
        ...
    
        /**
         * @param QuestionRequest $request
         * @param $id
         * @return \Illuminate\Http\RedirectResponse
         */
       /**
        *————————————————————————————
        * 此处通过以来注入验证类实现验证 |
        *————————————————————————————
        */
        public function update(QuestionRequest $request, $id)
        {
            $result = $this->question_repository->save($request->all(), $id);
            if ( $result ) {
                flash('修改成功', 'success');
    
                return redirect()->route('questions.show', $id);
            } else {
                flash('修改失败', 'danger');
    
                return redirect()->route('questions.show', $id);
            }
        }    
        ...
        
    }

   ```

   
   - Repository模式 使数据库操作与控制器分离

   ```
   /**
     * Class QuestionRepository
     * @package App\Repositories
     */
    class QuestionRepository
    {
        /**
         * @param $id
         * @return mixed
         */
        public function byIdWithTopic($id)
        {
            $question = Question::where('id', $id)->with(['topic', 'user', 'answers'])->first();
    
            return $question;
        }
    
        /**
         * @return mixed
         */
        public function getAll()
        {
            return Question::published()->with('answers')->latest('updated_at')->paginate(12);
        }
    
    
        /**
         * @param array $attributes
         * @param User $user
         * @return mixed
         */
        public function create(array $attributes, User $user)
        {
            $user->increment('questions_count');
    
            return Question::create($attributes);
        }
    
        /**
         * @param array $attributes
         * @param $id
         * @return bool
         */
        public function save(array $attributes, $id)
        {
            $question = Question::find($id);
            if ( Auth::user()->can('update', $question) ) {
                $question->topic()->sync($attributes['topics'] ?? []);
                $question->update($attributes);
    
                return true;
            } else {
                return false;
            }
        }
    
        /**
         * @param $id
         * @return bool
         */
        public function del($id)
        {
            $question = Question::find($id);
            if ( Auth::user()->can('delete', $question) ) {
                return $question->delete();
            }
    
            return false;
        }
    
        /**
         * @param $question_id
         * @return bool
         */
        public function follow($question_id, $user_id)
        {
            $question = Question::find($question_id);
            $result   = $question->followThis($user_id);
            $this->updateFollowersCount($question, !empty($result['attached']));
    
            return $result;
        }
    
        /**
         * @param Question $question
         * @param $state
         * @return int
         */
        private function updateFollowersCount(Question $question, $state)
        {
            if ( $state ) {
                return $question->increment('followers_count');
            } else {
                return $question->decrement('followers_count');
            }
        }
    
        /**
         * @param $question_id
         * @param $user_id
         * @return mixed
         */
        public function isFollow($question_id, $user_id)
        {
            $question = Question::find($question_id);
    
            return $question->isFollow($user_id);
        }
    
    }
   ```
   - 集合：Collection 组合数据

   ```
   /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\QuestionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {
        $topics = $request->topics;
        $user = Auth::user();
        collect($topics)->map(function ($topic) {
            $this->topic_repository->incrementQuestionCount($topic);
        });

        $data = array_merge(['user_id' => $user->id], $request->all());

        $question = $this->question_repository->create($data,$user);

        $question->topic()->attach($topics);

        return redirect(route('questions.index'));
    }
   ```
   
   - Redis缓存

   ```
   if ( !function_exists('pushRedisQuestions') ) {

    /**
     * 向redis的questions表中添加一个question
     * @param $id
     * @param $question
     * @return bool
     */
    function pushRedisQuestions($id, $question)
        {
            try{
                return Redis::hset('questions',$id,json_encode($question));
            }catch (ConnectionException $exception){
                Log::error($exception->getMessage());
                return false;
            }
        }
    }
   ```
   - 关系模型：Eloquent ORM
      - API资源：Resource 对接口返回的数据进行处理

      ```
      class TopicResource extends JsonResource
        {
        
            /**
             * Transform the resource into an array.
             *
             * @param  \Illuminate\Http\Request $request
             * @return array
             */
            public function toArray($request)
            {
                return [
                    'id'   => $this->id,
                    'text' => $this->name,
                ];
            }
        }
      ```
      
      - 模型关联/多态关联

      ```
      class Comments extends Model
        {
            protected $table = 'comments';
        
            protected $fillable = ['user_id','body','commentable_id','commentable_type'];
        
            public function commentable()
            {
                return $this->morphTo();
            }
        
            public function user()
            {
                return $this->belongsTo(User::class);
            }
    }
      ```
   - Passport认证 实现OAuth2.0

- 扩展包

| 扩展包名称| 描述 | 应用场景 |
| :-: | :-: | :-: |
| laracasts/flash | 消息提示 | 登录后浏览器上方可以看到 |
| laravel/passport | 身份验证 | 实现OAuth2.0  url: /my/passport |
| naux/sendcloud  | 邮件发送 | 注册邮箱验证及密码找回 |
| overtrue/laravel-ueditor | 编辑器 | 提问页面可查看 |
| predis/predis | Redis扩展 | 缓存了提问数据 |


--
###前端代码
- 框架
   - Vue.js 主要使用了vue-component 做了 关注用户 关注问题等组件
   - Blade 模板

- 扩展包
   - Select2 提问页面-话题
   - Axios 处理HTTP请求
   - Laravel mix 编译前端文件
   - Vue-image-crop-upload 图片裁剪上传
   - Bootstrap 4 页面部署 
