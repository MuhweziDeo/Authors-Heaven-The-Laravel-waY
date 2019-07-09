import { IAuthor } from './author.model';

export interface IArticle {
slug: string;
title: string;
description: string;
body: string;
image?: string;
isFavourite: boolean;
averageRating: number | null;
hasRated: boolean;
currenUserRating?: number | null;
hasBookMarked: boolean;
created_at: string;
author: IAuthor;
likes: Array<any>;
comments: Array<any>;
favourites: Array<any>;
dis_likes: Array<any>;


}