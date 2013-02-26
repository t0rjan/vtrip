//
//  customAnnotation.h
//  trip
//
//  Created by 沈 吾苓 on 13-2-7.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <MapKit/MapKit.h>

@interface customAnnotation : NSObject <MKAnnotation>

- (id) initWithCoordinate:(CLLocationCoordinate2D) coords;

@property (nonatomic , readonly) CLLocationCoordinate2D coordinate;
@property (retain , nonatomic) NSString *title;
@property (nonatomic , retain) NSString *subtitle;

@end
