/*
 * http://share42.com
 * (c) 2011 Dimox
 */
function share42(f,u,t){if(!u)u=location.href;if(!t)t=document.title;u=encodeURIComponent(u);t=encodeURIComponent(t);var s=new Array('http://www.blogger.com/blog_this.pyra?t&u='+u+'&n='+t+'" title="Опубликовать в Blogger.com"','http://bobrdobr.ru/add.html?url='+u+'&title='+t+'" title="Забобрить"','http://www.facebook.com/sharer.php?u='+u+'&t='+t+'" title="Поделиться в Facebook"','http://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk='+u+'&title='+t+'" title="Сохранить закладку в Google"','http://www.liveinternet.ru/journal_post.php?action=n_add&cnurl='+u+'&cntitle='+t+'" title="Опубликовать в LiveInternet"','http://www.livejournal.com/update.bml?event='+u+'&subject='+t+'" title="Опубликовать в LiveJournal"','http://connect.mail.ru/share?url='+u+'&title='+t+'" title="Поделиться в Моем Мире@Mail.Ru"','http://memori.ru/link/?sm=1&u_data[url]='+u+'&u_data[name]='+t+'" title="Сохранить закладку в Memori.ru"','http://www.myspace.com/Modules/PostTo/Pages/?u='+u+'&t='+t+'" title="Добавить в MySpace"','http://www.odnoklassniki.ru/dk?st.cmd=addShare&st._surl='+u+'&title='+t+'" title="Добавить в Одноклассники"','#" onclick="print();return false" title="Распечатать"','http://twitter.com/share?text='+t+'&url='+u+'" title="Добавить в Twitter"','http://vkontakte.ru/share.php?url='+u+'" title="Поделиться В Контакте"','http://bookmarks.yahoo.com/toolbar/savebm?u='+u+'&t='+t+'" title="Добавить в Yahoo! Закладки"','http://zakladki.yandex.ru/newlink.xml?url='+u+'&name='+t+'" title="Добавить в Яндекс.Закладки"');for(i=0;i<s.length;i++)document.write('<a style="display:inline-block;width:24px;height:24px;margin:0 7px 0 0;background:url(http://'+f+'icons.png) -'+24*i+'px 0" href="'+s[i]+'" target="_blank"></a>')}